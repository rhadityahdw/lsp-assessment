<script setup lang="ts">
const currentYear = new Date().getFullYear();
</script>

<template>
    <footer class="bg-gray-100 border-t border-gray-200">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Logo & Description -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">LSP Digital</h3>
                    <p class="text-sm text-gray-600">
                        Lembaga Sertifikasi Profesi yang berkomitmen untuk meningkatkan kompetensi SDM di bidang digital.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Tautan Cepat</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="/schemes" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">
                                Skema Sertifikasi
                            </a>
                        </li>
                        <li>
                            <a href="/assessments" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">
                                Asesmen
                            </a>
                        </li>
                        <li>
                            <a href="/contact" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">
                                Hubungi Kami
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Kontak</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span>Jl. Digital No. 123, Jakarta</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                            <span>info@lsp-digital.com</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                            <span>+62 21 1234 5678</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    © {{ currentYear }} LSP Digital. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </footer>
</template>