<?php

declare(strict_types=1);

namespace App\Enums;

enum UserType: string
{
    case PlatformSuperAdmin = 'platform_super_admin';
    case PlatformSupport = 'platform_support';
    case TenantOwner = 'tenant_owner';
    case TenantAdmin = 'tenant_admin';
    case OperationsManager = 'operations_manager';
    case WarehouseManager = 'warehouse_manager';
    case ReceivingClerk = 'receiving_clerk';
    case Inspector = 'inspector';
    case RestockingOperator = 'restocking_operator';
    case RefurbishmentTechnician = 'refurbishment_technician';
    case CustomerServiceAgent = 'customer_service_agent';
    case Finance = 'finance';
    case Merchant = 'merchant';
    case Supplier = 'supplier';
    case Carrier = 'carrier';
    case Customer = 'customer';
    case Buyer = 'buyer';
    case Auditor = 'auditor';
    case ApiClient = 'api_client';

    public function label(): string
    {
        return match ($this) {
            self::PlatformSuperAdmin => 'Platform Super Admin',
            self::PlatformSupport => 'Platform Support Staff',
            self::TenantOwner => 'Tenant Owner',
            self::TenantAdmin => 'Tenant Admin',
            self::OperationsManager => 'Operations Manager',
            self::WarehouseManager => 'Warehouse Manager',
            self::ReceivingClerk => 'Receiving Clerk',
            self::Inspector => 'Inspector / QC Technician',
            self::RestockingOperator => 'Restocking / Putaway Operator',
            self::RefurbishmentTechnician => 'Refurbishment Technician',
            self::CustomerServiceAgent => 'Customer Service Agent',
            self::Finance => 'Finance / Accountant',
            self::Merchant => 'Merchant / Brand Client',
            self::Supplier => 'Supplier / Vendor',
            self::Carrier => 'Carrier / Logistics Partner',
            self::Customer => 'Customer',
            self::Buyer => 'Buyer',
            self::Auditor => 'Auditor / Read-only',
            self::ApiClient => 'API Client',
        };
    }

    public function isPlatform(): bool
    {
        return in_array($this, [self::PlatformSuperAdmin, self::PlatformSupport], true);
    }

    public function defaultRole(): string
    {
        return $this->value;
    }
}
