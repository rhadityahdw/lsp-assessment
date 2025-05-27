<script setup>
import { Head } from '@inertiajs/vue3';
import { CheckCircle2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
</script>

<template>
  <Head title="Pendaftaran Berhasil" />

  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-cyan-500 to-cyan-700 px-4 py-12">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-8 text-center">
      <div class="flex justify-center mb-4">
        <CheckCircle2 class="w-16 h-16 text-green-500" />
      </div>

      <h1 class="text-2xl font-bold text-gray-800 mb-2">Pendaftaran Berhasil!</h1>
      <p class="text-gray-600 mb-6">
        Terima kasih telah mendaftar skema sertifikasi.<br>Kami akan segera memverifikasi data Anda dan menghubungi Anda selanjutnya.
      </p>

      <Button variant="default" :href="route('home')" class="bg-cyan-700" as-child>
        <a>Kembali ke Beranda</a>
      </Button>
    </div>
  </div>
</template>
