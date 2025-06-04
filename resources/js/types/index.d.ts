import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
    roles: Role;
    permissions: Permissions;
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
    roles: Role[]
    profile?: Profile | null; // Tambahkan ini
    created_at: string;
    updated_at: string;
}

export interface Role {
    id: number;
    name: string;
    guard_name: string;
}

export interface Profile {
    id: number;
    user_id: number;
    user: User;
    nik: string;
    gender: 'male' | 'female';
    date_of_birth: string;
    place_of_birth: string;
    address: string;
    phone_number: string;
    education: string;
    job_title: string;
    company_name: string | null;
    company_address: string | null;
    company_phone: string | null;
    company_email: string | null;
    created_at: string;
    updated_at: string;
}

export interface Scheme {
    id: number;
    code: string;
    name: string;
    type: string;
    document_path: Text;
    summary: Text;
    units: Unit[];
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
    pivot: {
        scheme_id: number
        unit_id: number
    }
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
