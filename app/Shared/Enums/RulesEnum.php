<?php

namespace App\Shared\Enums;

enum RulesEnum: string
{
    case USERS_TECHNICAL_SUPPORT_UPDATE_STATUS = 'USERS_TECHNICAL_SUPPORT_UPDATE_STATUS';
    case USERS_ADMIN_MASTER_UPDATE_STATUS = 'USERS_ADMIN_MASTER_UPDATE_STATUS';
    case USERS_ADMIN_CHURCH_UPDATE_STATUS = 'USERS_ADMIN_CHURCH_UPDATE_STATUS';
    case USERS_ADMIN_MODULE_UPDATE_STATUS = 'USERS_ADMIN_MODULE_UPDATE_STATUS';
    case USERS_ASSISTANT_UPDATE_STATUS = 'USERS_ASSISTANT_UPDATE_STATUS';
    case ADMIN_USERS_SUPPORT_VIEW   = 'ADMIN_USERS_SUPPORT_VIEW';
    case ADMIN_USERS_SUPPORT_INSERT = 'ADMIN_USERS_SUPPORT_INSERT';
    case ADMIN_USERS_SUPPORT_UPDATE = 'ADMIN_USERS_SUPPORT_UPDATE';

    case ADMIN_USERS_ADMIN_MASTER_VIEW   = 'ADMIN_USERS_ADMIN_MASTER_VIEW';
    case ADMIN_USERS_ADMIN_MASTER_INSERT = 'ADMIN_USERS_ADMIN_MASTER_INSERT';
    case ADMIN_USERS_ADMIN_MASTER_UPDATE = 'ADMIN_USERS_ADMIN_MASTER_UPDATE';

    case ADMIN_USERS_ADMIN_CHURCH_VIEW   = 'ADMIN_USERS_ADMIN_CHURCH_VIEW';
    case ADMIN_USERS_ADMIN_CHURCH_INSERT = 'ADMIN_USERS_ADMIN_CHURCH_INSERT';
    case ADMIN_USERS_ADMIN_CHURCH_UPDATE = 'ADMIN_USERS_ADMIN_CHURCH_UPDATE';
    case ADMIN_USERS_ADMIN_CHURCH_DELETE = 'ADMIN_USERS_ADMIN_CHURCH_DELETE';

    case ADMIN_USERS_ADMIN_MODULE_VIEW   = 'ADMIN_USERS_ADMIN_MODULE_VIEW';
    case ADMIN_USERS_ADMIN_MODULE_INSERT = 'ADMIN_USERS_ADMIN_MODULE_INSERT';
    case ADMIN_USERS_ADMIN_MODULE_UPDATE = 'ADMIN_USERS_ADMIN_MODULE_UPDATE';
    case ADMIN_USERS_ADMIN_MODULE_DELETE = 'ADMIN_USERS_ADMIN_MODULE_DELETE';

    case ADMIN_USERS_ASSISTANT_VIEW   = 'ADMIN_USERS_ASSISTANT_VIEW';
    case ADMIN_USERS_ASSISTANT_INSERT = 'ADMIN_USERS_ASSISTANT_INSERT';
    case ADMIN_USERS_ASSISTANT_UPDATE = 'ADMIN_USERS_ASSISTANT_UPDATE';
    case ADMIN_USERS_ASSISTANT_DELETE = 'ADMIN_USERS_ASSISTANT_DELETE';

    case ADMIN_USERS_MEMBER_VIEW   = 'ADMIN_USERS_MEMBER_VIEW';
    case ADMIN_USERS_MEMBER_INSERT = 'ADMIN_USERS_MEMBER_INSERT';
    case ADMIN_USERS_MEMBER_UPDATE = 'ADMIN_USERS_MEMBER_UPDATE';
    case ADMIN_USERS_MEMBER_DELETE = 'ADMIN_USERS_MEMBER_DELETE';

    case PROFILES_SUPPORT_VIEW = 'PROFILES_SUPPORT_VIEW';
    case PROFILES_ADMIN_MASTER_VIEW = 'PROFILES_ADMIN_MASTER_VIEW';
    case PROFILES_ADMIN_CHURCH_VIEW = 'PROFILES_ADMIN_CHURCH_VIEW';
    case PROFILES_ADMIN_MODULE_VIEW = 'PROFILES_ADMIN_MODULE_VIEW';
    case PROFILES_ASSISTANT_VIEW = 'PROFILES_ASSISTANT_VIEW';
    case PROFILES_MEMBER_VIEW = 'PROFILES_MEMBER_VIEW';

    case COUNT_USERS_ADMIN_MASTER_VIEW = 'COUNT_USERS_ADMIN_MASTER_VIEW';
    case COUNT_USERS_ADMIN_CHURCH_VIEW = 'COUNT_USERS_ADMIN_CHURCH_VIEW';
    case COUNT_USERS_ADMIN_MODULE_VIEW = 'COUNT_USERS_ADMIN_MODULE_VIEW';
    case COUNT_USERS_ASSISTANT_VIEW = 'COUNT_USERS_ASSISTANT_VIEW';

    case CITIES_VIEW = 'CITIES_VIEW';
    case STATES_VIEW = 'STATES_VIEW';
    case SYSTEM_VIEW = 'SYSTEM_VIEW';
    case SYSTEM_UPDATE = 'SYSTEM_UPDATE';

    case MEMBERSHIP_MODULE_CHURCH_VIEW = 'MEMBERSHIP_MODULE_CHURCH_VIEW';
    case MEMBERSHIP_MODULE_CHURCH_DETAILS_VIEW = 'MEMBERSHIP_MODULE_CHURCH_DETAILS_VIEW';
    case MEMBERSHIP_MODULE_CHURCH_INSERT = 'MEMBERSHIP_MODULE_CHURCH_INSERT';
    case MEMBERSHIP_MODULE_CHURCH_UPDATE = 'MEMBERSHIP_MODULE_CHURCH_UPDATE';

    case MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_VIEW = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_VIEW';
    case MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DETAILS_VIEW = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DETAILS_VIEW';
    case MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT';
    case MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_UPDATE = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_UPDATE';
    case MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DELETE = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DELETE';
    case MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_IMAGE_UPLOAD = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_IMAGE_UPLOAD';

    case MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_VIEW = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_VIEW';
    case MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW';
    case MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_UPDATE = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_UPDATE';
    case MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_IMAGE_UPLOAD = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_IMAGE_UPLOAD';

    case MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_VIEW = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_VIEW';
    case MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_DETAILS_VIEW = 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_DETAILS_VIEW';

    case MEMBERSHIP_MODULE_CHURCH_ASSISTANT_VIEW = 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT_VIEW';
    case MEMBERSHIP_MODULE_CHURCH_ASSISTANT_DETAILS_VIEW = 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT_DETAILS_VIEW';

    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_VIEW = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_VIEW';
    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_VIEW = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_VIEW';
    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_VIEW = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_VIEW';
    case MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_VIEW = 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_VIEW';

    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_DETAILS_VIEW = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_DETAILS_VIEW';
    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_INSERT = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_INSERT';
    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE';
    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_IMAGE_UPLOAD = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_IMAGE_UPLOAD';

    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_DETAILS_VIEW = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_DETAILS_VIEW';
    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_INSERT = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_INSERT';
    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE';
    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_IMAGE_UPLOAD = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_IMAGE_UPLOAD';

    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_DETAILS_VIEW = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_DETAILS_VIEW';
    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_INSERT = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_INSERT';
    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE';
    case MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_IMAGE_UPLOAD = 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_IMAGE_UPLOAD';

    case MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_DETAILS_VIEW = 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_DETAILS_VIEW';
    case MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_INSERT = 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_INSERT';
    case MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE = 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE';
    case MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_IMAGE_UPLOAD = 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_IMAGE_UPLOAD';
    case MEMBERSHIP_MODULE_MEMBERS_PROFILES_FILTER_VIEW = 'MEMBERSHIP_MODULE_MEMBERS_PROFILES_FILTER_VIEW';
    case MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_ADMIN_MASTER_VIEW = 'MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_ADMIN_MASTER_VIEW';
    case MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_VIEW = 'MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_VIEW';
    case MODULES_VIEW = 'MODULES_VIEW';
    case USERS_EMAIL_ALREADY_EXISTS_VERIFICATION_VIEW = 'USERS_EMAIL_ALREADY_EXISTS_VERIFICATION_VIEW';

    case USERS_IMAGE_UPLOAD_ADMIN_MASTER = 'USERS_IMAGE_UPLOAD_ADMIN_MASTER';
    case USERS_IMAGE_UPLOAD_ADMIN_CHURCH = 'USERS_IMAGE_UPLOAD_ADMIN_CHURCH';
    case USERS_IMAGE_UPLOAD_ADMIN_MODULE = 'USERS_IMAGE_UPLOAD_ADMIN_MODULE';
    case USERS_IMAGE_UPLOAD_ADMIN_ASSISTANT = 'USERS_IMAGE_UPLOAD_ADMIN_ASSISTANT';

    case STORE_MODULE_DEPARTMENTS_VIEW   = 'STORE_MODULE_DEPARTMENTS_VIEW';
    case STORE_MODULE_DEPARTMENTS_INSERT = 'STORE_MODULE_DEPARTMENTS_INSERT';
    case STORE_MODULE_DEPARTMENTS_UPDATE = 'STORE_MODULE_DEPARTMENTS_UPDATE';
    case STORE_MODULE_DEPARTMENTS_DELETE = 'STORE_MODULE_DEPARTMENTS_DELETE';
    case STORE_MODULE_DEPARTMENTS_STATUS_UPDATE = 'STORE_MODULE_DEPARTMENTS_STATUS_UPDATE';

    case STORE_MODULE_SUBCATEGORIES_VIEW   = 'STORE_MODULE_SUBCATEGORIES_VIEW';
    case STORE_MODULE_SUBCATEGORIES_INSERT = 'STORE_MODULE_SUBCATEGORIES_INSERT';
    case STORE_MODULE_SUBCATEGORIES_UPDATE = 'STORE_MODULE_SUBCATEGORIES_UPDATE';
    case STORE_MODULE_SUBCATEGORIES_DELETE = 'STORE_MODULE_SUBCATEGORIES_DELETE';
    case STORE_MODULE_SUBCATEGORIES_STATUS_UPDATE = 'STORE_MODULE_SUBCATEGORIES_STATUS_UPDATE';

    case STORE_MODULE_PRODUCTS_VIEW          = 'STORE_MODULE_PRODUCTS_VIEW';
    case STORE_MODULE_PRODUCTS_INSERT        = 'STORE_MODULE_PRODUCTS_INSERT';
    case STORE_MODULE_PRODUCTS_UPDATE        = 'STORE_MODULE_PRODUCTS_UPDATE';
    case STORE_MODULE_PRODUCTS_DELETE        = 'STORE_MODULE_PRODUCTS_DELETE';
    case STORE_MODULE_PRODUCTS_STATUS_UPDATE = 'STORE_MODULE_PRODUCTS_STATUS_UPDATE';

    case UNIQUE_CODE_PREFIXES_VIEW = 'UNIQUE_CODE_PREFIXES_VIEW';
    case UNIQUE_CODE_PREFIXES_INSERT = 'UNIQUE_CODE_PREFIXES_INSERT';
    case UNIQUE_CODE_PREFIXES_UPDATE = 'UNIQUE_CODE_PREFIXES_UPDATE';
    case UNIQUE_CODE_PREFIXES_DELETE = 'UNIQUE_CODE_PREFIXES_DELETE';
    case UNIQUE_CODE_GENERATOR = 'UNIQUE_CODE_GENERATOR';
}
