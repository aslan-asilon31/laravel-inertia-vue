<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Products" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
      <Card>
        <CardHeader>
          <CardTitle>Product Page</CardTitle>
        </CardHeader>
      </Card>

      <Card>
        <Container>
          <div class="m-2 flex items-center justify-between gap-4">
            <Button @click="toggleSheet" class="mb-4">
              Advanced Search
            </Button>
            <Button @click="goToCreatePage">Create Product</Button>
          </div>

          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>No</TableHead>
                <TableHead>Nama Produk</TableHead>
                <TableHead>Harga</TableHead>
                <TableHead>Ketersediaan</TableHead>
                <TableHead>Image URL</TableHead>
                <TableHead>Created By</TableHead>
                <TableHead>Updated By</TableHead>
                <TableHead>Created At</TableHead>
                <TableHead>Updated At</TableHead>
                <TableHead>Status</TableHead>
                <TableHead>Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="(product, index) in products.data" :key="product.id">
                <TableCell>{{ index + 1 + (products.current_page - 1) * products.per_page }}</TableCell>
                <TableCell>{{ product.name }}</TableCell>
                <TableCell>Rp {{ product.selling_price }}</TableCell>
                <TableCell>{{ product.availability }}</TableCell>
                <TableCell>{{ product.image_url }}</TableCell>
                <TableCell>{{ product.created_by }}</TableCell>
                <TableCell>{{ product.updated_by }}</TableCell>
                <TableCell>{{ formatDateTime(product.created_at) }}</TableCell>
                <TableCell>{{ formatDateTime(product.updated_at) }}</TableCell>
                <TableCell :class="getStatusClass(product.is_activated, product.created_at)">
                  {{ product.is_activated ? "Active" : "Inactive" }}
                </TableCell>
                <TableCell class="relative">
                  <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                      <Button variant="outline"><ChevronRightIcon /></Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="w-56" align="start">
                      <DropdownMenuGroup>
                        <DropdownMenuItem>
                          <Link :href="route('products.edit', product.id)">
                            <Button variant="outline">Edit</Button>
                          </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem>
                          <Link :href="route('products.show', product.id)">
                            <Button variant="outline">Show</Button>
                          </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem>
                          <Button variant="outline" @click="handleDelete(product.id)">Delete</Button>
                        </DropdownMenuItem>
                      </DropdownMenuGroup>
                      <DropdownMenuSeparator />
                    </DropdownMenuContent>
                  </DropdownMenu>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </Container>
      </Card>

      <div class="flex items-center justify-center">
        <Pagination :links="products.links" />
      </div>

      <!-- Advanced Search Sheet -->
      <Sheet :open="isSheetOpen" @openChange="toggleSheet">
        <SheetContent class="overflow-y-auto max-h-[190vh]">
          <SheetHeader>
            <SheetTitle>Advanced Search</SheetTitle>
            <SheetDescription>
              Filter products by various criteria.
            </SheetDescription>
          </SheetHeader>
          
          <!-- Form for Advanced Search -->
          <form @submit.prevent="submit">
            <div class="grid flex-1 auto-rows-min gap-6 px-4">
              <div class="grid gap-3">
                <Label for="product-name">Product Name</Label>
                <Input v-model="formData.search" id="product-name" placeholder="Search by name" />
                <div v-if="errors.search" class="text-red-500">{{ errors.search }}</div>
              </div>

              <div class="grid gap-3">
                <Label for="availability">Availability</Label>
                <select v-model="formData.availability" class="border p-2 rounded-md">
                  <option value="">All</option>
                  <option value="in-stock">In Stock</option>
                  <option value="out-of-stock">Out of Stock</option>
                </select>
                <div v-if="errors.availability" class="text-red-500">{{ errors.availability }}</div>
              </div>

              <!-- Additional Fields (if needed) -->

              <SheetFooter>
                <Button :disabled="processing" type="submit">Search</Button>
                <Button @click="handleClose" variant="outline">Close</Button>
              </SheetFooter>
            </div>
          </form>
        </SheetContent>
      </Sheet>
      <!-- End Advanced Search Sheet -->
    </div>
  </AppLayout>
</template>

<script>
import { ref, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Button, Input, Label, Card, Sheet, SheetContent, SheetFooter, SheetHeader, SheetTitle, SheetDescription } from '@/components/ui';
import { DropdownMenu, DropdownMenuContent, DropdownMenuGroup, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { ChevronRightIcon } from 'lucide-icons';
import Pagination from "@/components/pagination";
import Swal from "sweetalert2";

export default {
  name: "ProductIndex",
  props: {
    products: Object,
    filters: Object
  },
  setup(props) {
    const breadcrumbs = ref([
      { title: 'Product', href: '/product' }
    ]);

    const formData = reactive({
      search: props.filters.search || "",
      availability: props.filters.availability || "",
      selling_price: props.filters.selling_price || "",
      image_url: props.filters.image_url || "",
      created_by: props.filters.created_by || "",
      updated_by: props.filters.updated_by || "",
      is_activated: props.filters.is_activated || "",
      created_at: props.filters.created_at || "",
      updated_at: props.filters.updated_at || "",
    });

    const errors = reactive({});
    const processing = ref(false);
    const isSheetOpen = ref(false);

    const submit = () => {
      Inertia.get(route("products.index"), formData);
    };

    const goToCreatePage = () => {
      window.location.href = route('products.create');
    };

    const handleDelete = (productId) => {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          Inertia.delete(route("products.destroy", productId), {
            onSuccess: () => {
              Swal.fire("Deleted!", "Your product has been deleted.", "success");
            },
            onError: () => {
              Swal.fire("Error", "Something went wrong, please try again.", "error");
            },
          });
        }
      });
    };

    const getStatusClass = (isActive, createdAt) => {
      const today = new Date().toISOString().split("T")[0]; 
      const createdDate = new Date(createdAt).toISOString().split("T")[0]; 

      if (isActive && createdDate === today) {
        return "text-green-500"; 
      }

      return ""; 
    };

    const formatDateTime = (date) => {
      const options = {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: false, 
      };
      return new Date(date).toLocaleString("en-GB", options).replace(",", "");
    };

    const toggleSheet = () => {
      isSheetOpen.value = !isSheetOpen.value;
    };

    const handleClose = () => {
      Object.assign(formData, {
        search: "",
        availability: "",
        selling_price: "",
        image_url: "",
        created_by: "",
        updated_by: "",
        is_activated: "",
        created_at: "",
        updated_at: "",
      });
      toggleSheet();
    };

    return {
      breadcrumbs,
      formData,
      errors,
      processing,
      isSheetOpen,
      submit,
      goToCreatePage,
      handleDelete,
      getStatusClass,
      formatDateTime,
      toggleSheet,
      handleClose
    };
  }
};
</script>

<style scoped>
/* You can add custom styles here */
</style>
