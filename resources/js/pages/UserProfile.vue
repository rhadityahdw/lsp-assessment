<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardAction, CardContent, CardDescription, CardFooter, CardHeader, CardTitle} from '@/components/ui/card';
import InputError from '@/components/InputError.vue';
import { onMounted, ref } from 'vue';
import Navbar from '@/components/Navbar.vue';
import { Profile } from '@/types';
import { useToast } from 'vue-toastification';
import Footer from '@/components/Footer.vue';
import { Textarea } from '@/components/ui/textarea';

const props = defineProps<{
    profile: Profile | null;
}>();

const errorMessage = ref('');
const isSubmitting = ref(false);
const toast = useToast();

const form = useForm({
    nik: '',
    gender: '',
    date_of_birth: '',
    place_of_birth: '',
    address: '',
    phone_number: '',
    education: '',
    job_title: '',
    company_name: '',
    company_address: '',
    company_phone: '',
    company_email: '',
});

onMounted(() => {
    if (props.profile) {
        form.clearErrors();
        form.reset();
        form.nik = props.profile.nik;
        form.gender = props.profile.gender;
        form.date_of_birth = props.profile.date_of_birth;
        form.place_of_birth = props.profile.place_of_birth;
        form.address = props.profile.address;
        form.phone_number = props.profile.phone_number;
        form.education = props.profile.education;
        form.job_title = props.profile.job_title;
        form.company_name = props.profile.company_name;
        form.company_address = props.profile.company_address;
        form.company_phone = props.profile.company_phone;
        form.company_email = props.profile.company_email;
    }
});

const handleSubmit = (form: any) => {
    form.post(route('profile.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            isSubmitting.value = false;
            toast.success('Profil berhasil disimpan');
        },
        onError: (errors: any) => {
            isSubmitting.value = false;
            if (errors.profile) {
                errorMessage.value = errors.profile;
                toast.error(errors.profile);
            } else {
                const firstError = Object.values(errors)[0];
                if (firstError) {
                    toast.error(firstError);
                }
            }
        }
    })
};

const handleUpdate = (form: any) => {
    form.put(route('profile.update'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            isSubmitting.value = false;
            toast.success('Profil berhasil diperbarui');
        },
        onError: (errors: any) => {
            isSubmitting.value = false;
            if (errors.profile) {
                errorMessage.value = errors.profile;
                toast.error(errors.profile);
            } else {
                const firstError = Object.values(errors)[0];
                if (firstError) {
                    toast.error(firstError);
                }
            }
        }
    })
};
</script>

<template>
    <Head title="Profil Pengguna" />

    <Navbar />

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <Card>
                <CardHeader>
                    <CardTitle class="text-2xl font-bold">Profil Pengguna</CardTitle>
                </CardHeader>
                <form @submit.prevent="(props.profile ? handleUpdate(form) : handleSubmit(form))">
                    <CardContent>
                        <!-- Informasi Pribadi -->
                        <div class="space-y-6">
                            <h1 class="text-lg">Informasi Pribadi</h1>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- NIK -->
                                <div class="space-y-2">
                                    <Label for="nik" required>NIK</Label>
                                    <Input 
                                        id="nik"
                                        v-model="form.nik"
                                        type="text" 
                                        placeholder="Nomor Induk Kependudukan" 
                                        maxlength="16"
                                        required
                                    />
                                    <InputError :message="form.errors.nik" />
                                </div>

                                <!-- Jenis Kelamin -->
                                <div class="space-y-2">
                                    <Label for="gender" required>Jenis Kelamin</Label>
                                    <Select v-model="form.gender" required>
                                    <SelectTrigger id="gender" class="w-full">
                                        <SelectValue placeholder="Pilih jenis kelamin" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="male">Laki-laki</SelectItem>
                                        <SelectItem value="female">Perempuan</SelectItem>
                                    </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.gender" />
                                </div>

                                <!-- Tempat Lahir -->
                                <div class="space-y-2">
                                    <Label for="place_of_birth" required>Tempat Lahir</Label>
                                    <Input 
                                        id="place_of_birth"
                                        v-model="form.place_of_birth"
                                        type="text" 
                                        placeholder="Tempat lahir" 
                                    />
                                    <InputError :message="form.errors.place_of_birth" />
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="space-y-2">
                                    <Label for="date_of_birth" required>Tanggal Lahir</Label>
                                    <Input 
                                        id="date_of_birth" 
                                        v-model="form.date_of_birth" 
                                        type="date"
                                        class=""
                                    />
                                    <InputError :message="form.errors.date_of_birth" />
                                </div>
                                
                                <!-- Nomor Telepon -->
                                <div class="space-y-2">
                                    <Label for="phone_number" required>Nomor Telepon</Label>
                                    <Input
                                        id="phone_number"
                                        v-model="form.phone_number"
                                        type="tel"
                                        placeholder="Nomor telepon"
                                        maxlength="15"
                                    />
                                    <InputError :message="form.errors.phone_number" />
                                </div>

                                <!-- Pendidikan -->
                                <div class="space-y-2">
                                    <Label for="education" required>Pendidikan</Label>
                                    <Input
                                        id="education"
                                        v-model="form.education"
                                        type="text" 
                                        placeholder="Pendidikan terakhir"
                                        maxlength="16"
                                    />
                                    <InputError :message="form.errors.education" />
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="space-y-2">
                                <Label for="address" required>Alamat</Label>
                                <Textarea
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    placeholder="Alamat lengkap"
                                />
                                <InputError :message="form.errors.address" />
                            </div>
                        </div>

                        <!-- Informasi Pekerjaan -->
                        <div class="space-y-6 border-t mt-8 pt-8">
                            <h1 class="text-lg">Informasi Pekerjaan</h1>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Pekerjaan -->
                                <div class="space-y-2">
                                    <Label for="job_title" required>Pekerjaan</Label>
                                    <Input
                                        id="job_title"
                                        v-model="form.job_title"
                                        type="text"
                                        placeholder="Pekerjaan saat ini"
                                    />
                                    <InputError :message="form.errors.job_title" />
                                </div>

                                <!-- Perusahaan -->
                                <div class="space-y-2">
                                    <Label for="company_name" required>Perusahaan</Label>
                                    <Input
                                        id="company_name"
                                        v-model="form.company_name"
                                        type="text"
                                        placeholder="Nama perusahaan"
                                    />
                                    <InputError :message="form.errors.company_name" />
                                </div>

                                <!-- Telepon Perusahaan -->
                                <div class="space-y-2">
                                    <Label for="company_phone">Telepon Perusahaan</Label>
                                    <Input 
                                        id="company_phone" 
                                        v-model="form.company_phone" 
                                        type="tel" 
                                        placeholder="Telepon perusahaan"
                                        maxlength="15" 
                                    />
                                    <InputError :message="form.errors.company_phone" />
                                </div>

                                <!-- Email Perusahaan -->
                                <div class="space-y-2">
                                    <Label for="company_email">Email Perusahaan</Label>
                                    <Input 
                                        id="company_email" 
                                        v-model="form.company_email" 
                                        type="email" 
                                        placeholder="Email perusahaan" 
                                    />
                                    <InputError :message="form.errors.company_email" />
                                </div>
                            </div>

                            <!-- Alamat Perusahaan -->
                            <div class="space-y-2">
                                <Label for="address" required>Alamat Perusahaan</Label>
                                <Textarea
                                    id="address"
                                    v-model="form.company_address"
                                    type="text"
                                    placeholder="Alamat perusahaan"
                                />
                                <InputError :message="form.errors.address" />
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter class="flex items-center justify-end mt-6">
                        <CardAction class="rounded-b-lg flex justify-end gap-2">
                            <Link :href="route('home')">
                                <Button type="button" variant="outline" tabindex="6">Cancel</Button>
                            </Link>
                            <Button type="submit" variant="default" :disabled="isSubmitting" tabindex="7">
                                {{ isSubmitting ? 'Memproses...' : (props.profile ? 'Perbarui Profil' : 'Simpan Profil') }}
                            </Button>
                        </CardAction>
                    </CardFooter>
                </form>
            </Card>
        </div>
    </div>

    <Footer />

</template>