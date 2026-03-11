import type { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';
import type { Post } from '@/types/laravel';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function resolveStatus(Post: Post): {text: string; variant: 'outline' | 'destructive' | 'default'} {
    if (Post.deleted_at) {
        return { text: 'Diarsipkan', variant: 'destructive' };
    }

    if (Post.published_at) {
        return { text: 'Diterbitkan', variant: 'default' };
    }

    return { text: 'Draft', variant: 'outline' };
}