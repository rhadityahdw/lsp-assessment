import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    role_id: number;
    role: Role;
    created_at: string;
    updated_at: string;
}

export interface Role {
    id: number;
    name: string;
}

export interface Scheme {
    id: number;
    code: string;
    name: string;
    type: string;
    document_path: Text;
    summary: Text;
    created_at: string;
    updated_at: string;
}

export interface Unit {
    id: number;
    code: string;
    name: string;
    type: string;
    scheme_id: number;
    scheme: Scheme;
    pre_assessments: PreAssessment[];
    created_at: string;
    updated_at: string;
}

export interface PreAssessment {
    id: number;
    unit_id: number;
    unit: Unit;
    question: string;
    expected_answer: 'yes' | 'no';
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
