<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Archive, LayoutGrid, Signpost } from 'lucide-vue-next';
import { computed } from 'vue';
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
        title: 'Aktif',
        href: PostController.index(),
        icon: Signpost,
    },
    {
        title: 'Arsip',
        href: PostController.archived(),
        icon: Archive,
    },
    {
        title: 'Semua Post',
        href: '/admin/posts',
        icon: Signpost,
        visible: isAdmin.value,
    }
]);

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
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
