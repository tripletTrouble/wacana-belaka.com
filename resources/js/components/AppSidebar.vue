<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Archive, LayoutGrid, Signpost, Tag } from 'lucide-vue-next';
import { computed } from 'vue';
import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
import PostController from '@/actions/App/Http/Controllers/PostController';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types/navigation';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const page = usePage();
const isAdmin = computed(() => page.props.auth?.is_admin ?? false);

const postNavItems = computed<NavItem[]>(() => [
    {
        title: 'Semua',
        href: '/admin/posts',
        icon: Signpost,
        visible: isAdmin.value,
    },
    {
        title: 'Aktif',
        href: PostController.index(),
        icon: Signpost,
        visible: !isAdmin.value,
    },
    {
        title: 'Arsip',
        href: PostController.archived(),
        icon: Archive,
        visible: isAdmin.value,
    }
]);

const settingNavItems: NavItem[] = [
    {
        title: 'Kategori Artikel',
        href: CategoryController.index(),
        icon: Tag,
    },
];

const footerNavItems: NavItem[] = [];

// page/isAdmin defined above
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
            <NavMain :items="postNavItems" label="Post" />
            <NavMain :items="settingNavItems" label="Pengaturan" v-if="isAdmin" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
