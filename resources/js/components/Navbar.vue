<script setup lang="ts">
import { computed, ref, onUnmounted } from "vue";
import { 
  DropdownMenu, 
  DropdownMenuTrigger, 
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator
} from "./ui/dropdown-menu";
import { Button } from "@/components/ui/button";
import { Avatar, AvatarFallback } from "./ui/avatar";
import { User2, Menu, X, Home, FileText, Award, Settings, LogOut, UserCircle, AlertCircle } from "lucide-vue-next";
import NavItem from "./ui/nav-item.vue";
import UserMenuButton from "./ui/user-menu-button.vue";
import { Link, usePage, router } from '@inertiajs/vue3';
import { SharedData, User } from "@/types";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog';

const page = usePage<SharedData>();
const currentRoute = computed(() => page.url);
const mobileMenuOpen = ref<boolean>(false);
const showProfileModal = ref<boolean>(false);

const toggleMobileMenu = (): void => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

const authUser = computed(() => page.props.auth.user);
const hasProfile = computed(() => authUser.value?.profile !== null);

// Handle navigation with profile check
const handleNavigation = (href: string, requiresProfile: boolean = false): void => {
    if (requiresProfile && !hasProfile.value) {
        showProfileModal.value = true;
        return;
    }
    router.visit(href);
};

const closeProfileModal = (): void => {
    showProfileModal.value = false;
};

const goToProfile = (): void => {
    showProfileModal.value = false;
    router.visit(route('profile.index'));
};

// Navigation items configuration
const navItems = [
  { id: 'home', label: 'Beranda', icon: Home, href: route('home'), requiresProfile: false },
  { id: 'pendaftaran', label: 'Pendaftaran', icon: FileText, href: route('pendaftaran'), requiresProfile: true },
  { id: 'skema', label: 'Skema Sertifikasi', icon: Award, href: route('skema'), requiresProfile: false },
  { id: 'assessments', label: 'Asesmen Saya', icon: FileText, href: route('asesi.assessments.index'), requiresProfile: false }
];

// Determine active item based on current route
const activeItem = computed(() => {
  const currentUrl = currentRoute.value;
  
  return navItems.find(item => {
    try {
      if (item.href) {
        const itemPath = new URL(item.href).pathname;
        return currentUrl === itemPath;
      }
    } catch (error) {
      console.error(`Error parsing URL for ${item.id}:`, error);
    }
    return false;
  })?.id || '';
});

// Close mobile menu when navigating
const setActive = (): void => {
  mobileMenuOpen.value = false;
};

// Handle click outside to close mobile menu
const handleClickOutside = (event: Event): void => {
  const target = event.target as Element;
  if (!target.closest('.mobile-menu-container') && !target.closest('.mobile-menu-button')) {
    mobileMenuOpen.value = false;
  }
};

// Add event listener for click outside
if (typeof window !== 'undefined') {
  document.addEventListener('click', handleClickOutside);
}

// Clean up event listener on component unmount
onUnmounted(() => {
  if (typeof window !== 'undefined') {
    document.removeEventListener('click', handleClickOutside);
  }
});
</script>

<template>
    <header class="border-b shadow-sm bg-white sticky top-0 z-50">
        <div class="container mx-auto px-3 sm:px-4 lg:px-6 py-2 sm:py-3">
            <div class="flex items-center justify-between">
                <!-- Logo Section -->
                <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-shrink-0">
                    <img 
                        src="/images/logo.png" 
                        alt="LSP Logo" 
                        class="h-8 sm:h-10 md:h-12 flex-shrink-0" 
                        loading="lazy"
                    />
                    <div class="hidden xs:block sm:block min-w-0 max-w-xs sm:max-w-none">
                        <span class="font-semibold text-xs sm:text-sm md:text-base lg:text-lg text-gray-800 truncate block leading-tight">
                            Lembaga Sertifikasi Profesi
                        </span>
                        <p class="text-xs text-gray-500 -mt-0.5 hidden sm:block truncate">
                            Teknologi Digital
                        </p>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <button 
                    @click="toggleMobileMenu" 
                    class="lg:hidden p-2 rounded-md hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 mobile-menu-button"
                    :aria-expanded="mobileMenuOpen"
                    aria-label="Toggle navigation menu"
                >
                    <Menu v-if="!mobileMenuOpen" class="h-5 w-5 sm:h-6 sm:w-6 text-gray-700" />
                    <X v-else class="h-5 w-5 sm:h-6 sm:w-6 text-gray-700" />
                </button>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-4 xl:space-x-6">
                    <!-- Navigation Menu -->
                    <nav role="navigation" aria-label="Main navigation">
                        <ul class="flex space-x-1">
                            <li v-for="item in navItems" :key="item.id">
                                <button
                                    v-if="item.requiresProfile"
                                    @click="handleNavigation(item.href, item.requiresProfile)"
                                    class="inline-flex"
                                >
                                    <NavItem 
                                        :active="activeItem === item.id"
                                        :icon="item.icon"
                                        :label="item.label"
                                        :onClick="() => setActive()"
                                    />
                                </button>
                                <Link v-else :href="item.href">
                                    <NavItem 
                                        :active="activeItem === item.id"
                                        :icon="item.icon"
                                        :label="item.label"
                                        :onClick="() => setActive()"
                                    />
                                </Link>
                            </li>
                        </ul>
                    </nav>

                    <!-- User Menu -->
                    <DropdownMenu>
                        <DropdownMenuTrigger class="rounded-full">
                            <UserMenuButton :user="authUser" />
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56 p-1">
                            <div class="p-2 mb-1 bg-gray-50 rounded-md">
                                <p class="font-medium text-gray-800 truncate">{{ authUser?.name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ authUser?.email }}</p>
                                <p class="text-xs text-cyan-600 mt-1 font-medium">{{ authUser?.roles?.[0] }}</p>
                            </div>
                            <Link :href="route('profile.index')">
                                <DropdownMenuItem class="cursor-pointer hover:bg-cyan-50 focus:bg-cyan-50 rounded-md">
                                    <UserCircle class="h-4 w-4 mr-2 text-gray-500" />
                                    Profil Saya
                                </DropdownMenuItem>
                            </Link>
                            <Link :href="route('asesi.certificates.index')">
                                <DropdownMenuItem class="cursor-pointer hover:bg-cyan-50 focus:bg-cyan-50 rounded-md">
                                    <FileText class="h-4 w-4 mr-2 text-gray-500" />
                                    Sertifikat
                                </DropdownMenuItem>
                            </Link>
                            <DropdownMenuItem class="cursor-pointer hover:bg-cyan-50 focus:bg-cyan-50 rounded-md">
                                <Settings class="h-4 w-4 mr-2 text-gray-500" />
                                Pengaturan
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <Link :href="route('logout')" method="post">
                                <DropdownMenuItem class="cursor-pointer hover:bg-red-50 focus:bg-red-50 text-red-600 rounded-md">
                                    <LogOut class="h-4 w-4 mr-2" />
                                    Keluar
                                </DropdownMenuItem>
                            </Link>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <div v-if="mobileMenuOpen" class="lg:hidden mt-3 sm:mt-4 mobile-menu-container">
                    <nav class="bg-white rounded-lg p-3 shadow-lg border border-gray-100" role="navigation" aria-label="Mobile navigation">
                        <ul class="flex flex-col space-y-1">
                            <li v-for="item in navItems" :key="item.id">
                                <button
                                    v-if="item.requiresProfile"
                                    @click="handleNavigation(item.href, item.requiresProfile)"
                                    class="w-full"
                                >
                                    <NavItem 
                                        :active="activeItem === item.id"
                                        :icon="item.icon"
                                        :label="item.label"
                                        :onClick="() => setActive()"
                                        class="w-full justify-start py-3 px-3 text-sm"
                                    />
                                </button>
                                <Link v-else :href="item.href">
                                    <NavItem 
                                        :active="activeItem === item.id"
                                        :icon="item.icon"
                                        :label="item.label"
                                        :onClick="() => setActive()"
                                        class="w-full justify-start py-3 px-3 text-sm"
                                    />
                                </Link>
                            </li>
                        </ul>
                    </nav>
                    
                    <!-- Mobile User Info -->
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <Avatar class="h-10 w-10 border border-gray-200 shadow-sm flex-shrink-0">
                                <AvatarFallback class="bg-gradient-to-br from-cyan-50 to-cyan-100 text-cyan-700">
                                    <User2 class="h-5 w-5" />
                                </AvatarFallback>
                            </Avatar>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-800 truncate">{{ authUser?.name }}</p>
                                <p class="text-xs text-cyan-600">{{ authUser?.roles?.[0] }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ authUser?.email }}</p>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1 bg-white rounded-lg overflow-hidden shadow-sm border border-gray-100">
                            <Link :href="route('profile.index')">
                                <Button variant="ghost" class="w-full justify-start text-gray-700 hover:bg-cyan-50 hover:text-cyan-600 px-4 py-3">
                                    <UserCircle class="h-4 w-4 mr-3 text-gray-500" />
                                    Profil Saya
                                </Button>
                            </Link>
                            <Link :href="route('asesi.certificates.index')">
                                <Button variant="ghost" class="w-full justify-start text-gray-700 hover:bg-cyan-50 hover:text-cyan-600 px-4 py-3">
                                    <FileText class="h-4 w-4 mr-3 text-gray-500" />
                                    Sertifikat
                                </Button>
                            </Link>
                            <Button variant="ghost" class="w-full justify-start text-gray-700 hover:bg-cyan-50 hover:text-cyan-600 px-4 py-3">
                                <Settings class="h-4 w-4 mr-3 text-gray-500" />
                                Pengaturan
                            </Button>
                            <div class="border-t border-gray-100 my-1"></div>
                            <Link :href="route('logout')" method="post">
                                <Button variant="ghost" class="w-full justify-start text-red-600 hover:bg-red-50 px-4 py-3">
                                    <LogOut class="h-4 w-4 mr-3" />
                                    Keluar
                                </Button>
                            </Link>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
        
        <!-- Profile Required Modal -->
        <Dialog :open="showProfileModal" @update:open="closeProfileModal">
            <DialogContent class="sm:max-w-md mx-4">
                <DialogHeader>
                    <DialogTitle class="flex items-center space-x-2 text-amber-600">
                        <AlertCircle class="h-5 w-5" />
                        <span>Profil Diperlukan</span>
                    </DialogTitle>
                    <DialogDescription class="text-left">
                        Anda harus melengkapi profil terlebih dahulu sebelum dapat mengakses halaman pendaftaran.
                    </DialogDescription>
                </DialogHeader>
                
                <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                    <div class="flex items-start space-x-3">
                        <AlertCircle class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5" />
                        <div class="min-w-0">
                            <h4 class="font-medium text-amber-800">Profil Belum Lengkap</h4>
                            <p class="text-sm text-amber-700 mt-1">
                                Silakan lengkapi profil Anda untuk mengakses fitur pendaftaran sertifikasi.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <Button @click="goToProfile" class="flex-1 bg-gradient-to-r from-cyan-600 to-cyan-700 hover:from-cyan-700 hover:to-cyan-800">
                        Lengkapi Profil
                    </Button>
                    <Button @click="closeProfileModal" variant="outline" class="flex-1">
                        Tutup
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
    </header>
</template>
