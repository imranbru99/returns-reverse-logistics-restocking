<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'customer.view', 'customer.create', 'customer.update', 'customer.delete',
            'product.view', 'product.create', 'product.update', 'product.delete',
            'order.view', 'order.create', 'order.update', 'order.delete',
            'rma.view', 'rma.create', 'rma.update', 'rma.delete', 'rma.transition',
            'inspection.view', 'inspection.create', 'inspection.update', 'inspection.delete',
            'inspection_checklist.view', 'inspection_checklist.create', 'inspection_checklist.update', 'inspection_checklist.delete',
            'carrier_account.view', 'carrier_account.create', 'carrier_account.update', 'carrier_account.delete',
            'shipment.view', 'shipment.create', 'shipment.update', 'shipment.delete',
            'receiving_record.view', 'receiving_record.create', 'receiving_record.update', 'receiving_record.delete',
            'putaway_task.view', 'putaway_task.create', 'putaway_task.update', 'putaway_task.delete',
            'vendor_claim.view', 'vendor_claim.create', 'vendor_claim.update', 'vendor_claim.delete',
            'refurbishment_work_order.view', 'refurbishment_work_order.create', 'refurbishment_work_order.update', 'refurbishment_work_order.delete',
            'disposition.view', 'disposition.create', 'disposition.update', 'disposition.delete',
            'refund.view', 'refund.create', 'refund.update', 'refund.delete',
            'store_credit_account.view', 'store_credit_account.create', 'store_credit_account.update', 'store_credit_account.delete',
            'fraud_signal.view', 'fraud_signal.create', 'fraud_signal.update', 'fraud_signal.delete',
            'exchange_order.view', 'exchange_order.create', 'exchange_order.update', 'exchange_order.delete',
            'warranty_claim.view', 'warranty_claim.create', 'warranty_claim.update', 'warranty_claim.delete',
            'marketplace_listing.view', 'marketplace_listing.create', 'marketplace_listing.update', 'marketplace_listing.delete',
            'support_ticket.view', 'support_ticket.create', 'support_ticket.update', 'support_ticket.delete',
            'knowledge_article.view', 'knowledge_article.create', 'knowledge_article.update', 'knowledge_article.delete',
            'work_task.view', 'work_task.create', 'work_task.update', 'work_task.delete',
            'modules.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $all = $permissions;

        $roleMatrix = [
            UserType::TenantOwner->value => $all,
            UserType::TenantAdmin->value => array_values(array_diff($all, ['modules.manage'])),
            UserType::OperationsManager->value => [
                'customer.view', 'product.view', 'order.view', 'order.update',
                'rma.view', 'rma.create', 'rma.update', 'rma.transition',
                'inspection.view', 'inspection.create', 'inspection.update',
                'inspection_checklist.view', 'inspection_checklist.update',
                'carrier_account.view', 'shipment.view', 'shipment.create',
                'receiving_record.view', 'receiving_record.create',
                'putaway_task.view', 'putaway_task.create', 'putaway_task.update',
                'vendor_claim.view', 'refurbishment_work_order.view',
                'disposition.view', 'disposition.create',
                'refund.view',
            ],
            UserType::CustomerServiceAgent->value => [
                'customer.view', 'customer.create', 'customer.update',
                'order.view',
                'rma.view', 'rma.create', 'rma.update',
                'refund.view', 'refund.create',
                'exchange_order.view', 'exchange_order.create',
                'support_ticket.view', 'support_ticket.create',
            ],
            UserType::Inspector->value => [
                'rma.view',
                'inspection.view', 'inspection.create', 'inspection.update',
                'inspection_checklist.view',
                'receiving_record.view', 'receiving_record.create',
            ],
            UserType::Finance->value => [
                'order.view', 'rma.view', 'refund.view', 'refund.create', 'refund.update',
                'store_credit_account.view', 'store_credit_account.create',
                'fraud_signal.view',
            ],
            UserType::Auditor->value => [
                'customer.view', 'product.view', 'order.view', 'rma.view', 'inspection.view', 'refund.view',
            ],
        ];

        foreach ($roleMatrix as $roleName => $rolePermissions) {
            $role = Role::query()->firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
                'tenant_id' => null,
            ]);

            $role->syncPermissions($rolePermissions);
        }
    }
}
