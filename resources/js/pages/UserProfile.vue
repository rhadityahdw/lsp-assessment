<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import InputError from '@/components/InputError.vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

// Mengambil data user dari usePage
const page = usePage();
const auth = page.props.auth;

// Form untuk data profil
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
  company_phone_number: '',
  company_email: '',
});

// Fungsi untuk menyimpan profil
const saveProfile = () => {
  form.post(route('profile.store'), {
    preserveScroll: true,
    onSuccess: () => {
      // Tampilkan pesan sukses jika diperlukan
    },
  });
};
</script>

<template>
  <Head title="Profil Pengguna" />

  <Navbar />

  <div class="container mx-auto py-8 px-4">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">Profil Pengguna</h1>

      <form @submit.prevent="saveProfile">
        <Card class="mb-6">
          <CardHeader>
            <CardTitle>Informasi Pribadi</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-2">
                <Label for="nik">NIK</Label>
                <Input id="nik" v-model="form.nik" placeholder="Masukkan NIK" />
                <InputError :message="form.errors.nik" />
              </div>

              <div class="space-y-2">
                <Label for="gender">Jenis Kelamin</Label>
                <Select v-model="form.gender">
                  <SelectTrigger>
                    <SelectValue placeholder="Pilih jenis kelamin" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="male">Laki-laki</SelectItem>
                    <SelectItem value="female">Perempuan</SelectItem>
                  </SelectContent>
                </Select>
                <InputError :message="form.errors.gender" />
              </div>

              <div class="space-y-2">
                <Label for="date_of_birth">Tanggal Lahir</Label>
                <Input id="date_of_birth" type="date" v-model="form.date_of_birth" />
                <InputError :message="form.errors.date_of_birth" />
              </div>

              <div class="space-y-2">
                <Label for="place_of_birth">Tempat Lahir</Label>
                <Input id="place_of_birth" v-model="form.place_of_birth" placeholder="Masukkan tempat lahir" />
                <InputError :message="form.errors.place_of_birth" />
              </div>

              <div class="space-y-2 md:col-span-2">
                <Label for="address">Alamat</Label>
                <Textarea id="address" v-model="form.address" placeholder="Masukkan alamat lengkap" />
                <InputError :message="form.errors.address" />
              </div>

              <div class="space-y-2">
                <Label for="phone_number">Nomor Telepon</Label>
                <Input id="phone_number" v-model="form.phone_number" placeholder="Masukkan nomor telepon" />
                <InputError :message="form.errors.phone_number" />
              </div>

              <div class="space-y-2">
                <Label for="education">Pendidikan Terakhir</Label>
                <Select v-model="form.education">
                  <SelectTrigger>
                    <SelectValue placeholder="Pilih pendidikan terakhir" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="SD">SD</SelectItem>
                    <SelectItem value="SMP">SMP</SelectItem>
                    <SelectItem value="SMA/SMK">SMA/SMK</SelectItem>
                    <SelectItem value="D3">D3</SelectItem>
                    <SelectItem value="S1">S1</SelectItem>
                    <SelectItem value="S2">S2</SelectItem>
                    <SelectItem value="S3">S3</SelectItem>
                  </SelectContent>
                </Select>
                <InputError :message="form.errors.education" />
              </div>
            </div>
          </CardContent>
        </Card>

        <Card class="mb-6">
          <CardHeader>
            <CardTitle>Informasi Pekerjaan</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-2">
                <Label for="job_title">Jabatan</Label>
                <Input id="job_title" v-model="form.job_title" placeholder="Masukkan jabatan" />
                <InputError :message="form.errors.job_title" />
              </div>

              <div class="space-y-2">
                <Label for="company_name">Nama Perusahaan</Label>
                <Input id="company_name" v-model="form.company_name" placeholder="Masukkan nama perusahaan" />
                <InputError :message="form.errors.company_name" />
              </div>

              <div class="space-y-2 md:col-span-2">
                <Label for="company_address">Alamat Perusahaan</Label>
                <Textarea id="company_address" v-model="form.company_address" placeholder="Masukkan alamat perusahaan" />
                <InputError :message="form.errors.company_address" />
              </div>

              <div class="space-y-2">
                <Label for="company_phone_number">Nomor Telepon Perusahaan</Label>
                <Input id="company_phone_number" v-model="form.company_phone_number" placeholder="Masukkan nomor telepon perusahaan" />
                <InputError :message="form.errors.company_phone_number" />
              </div>

              <div class="space-y-2">
                <Label for="company_email">Email Perusahaan</Label>
                <Input id="company_email" type="email" v-model="form.company_email" placeholder="Masukkan email perusahaan" />
                <InputError :message="form.errors.company_email" />
              </div>
            </div>
          </CardContent>
        </Card>

        <div class="flex justify-end">
          <Button type="submit" :disabled="form.processing" class="bg-gradient-to-r from-cyan-600 to-cyan-700 hover:from-cyan-700 hover:to-cyan-800 text-white shadow-sm">Simpan Profil</Button>
        </div>
      </form>
    </div>
  </div>
</template>