<?php

namespace App\Shared\Enums;

enum MessagesEnum: string {
    //General
    case METHOD_NOT_ALLOWED = 'Method not allowed.';
    case RESOURCE_NOT_FOUND = 'Resource not found.';
    case INTERNAL_SERVER_ERROR = 'Internal server error.';
    case UNAUTHORIZED = 'Unauthorized.';
    case NOT_FOUND = 'Not found.';

    case REGISTER_NOT_FOUND = 'Registro não encontrado.';
    case INVALID_UUID = 'O valor enviado não é um Uuid válido.';
    case INVALID_EMAIL = 'O valor enviado não é um E-mail válido.';
    case INVALID_UNIQUE_NAME = 'O valor enviado não é um nome único de produto válido.';
    case NOT_AUTHORIZED = 'Você não tem permissão para acessar este recurso.';
    case NOT_ENABLED = 'Você não tem permissão para acessar a plataforma. \n Para liberar ou verificar seu acesso entre em contato com o suporte.';

    // Sessions
    case LOGIN_ERROR = 'E-mail ou senha incorretos.';
    case INACTIVE_USER = 'Usuário não encontrado ou está inativo no sistema. Se necessário, entre em contato com o suporte.';
    case UNVERIFIED_EMAIL = 'E-mail não verificado.';
    case EMAIL_ALREADY_VERIFIED = 'Este usuário já teve seu e-mail verificado, se necessário entre em contato com o suporte.';
    case SUCCESS_MODIFY_PASSWORD = 'Senha redefinida com sucesso.';
    case INVALID_FORGOT_PASSWORD_CODE = 'Código de verificação expirado, por favor solicite uma nova troca de senha.';
    case PASSWORD_CODE_NOT_FOUND = 'Código de verificação não encontrado.';

    // Users
    case USER_NOT_FOUND = 'Usuário não encontrado.';
    case CODE_NOT_FOUND = 'Código não encontrado.';
    case INVALID_CODE = 'Código inválido.';
    case PROFILE_NOT_FOUND = 'Perfil não encontrado.';
    case EMAIL_ALREADY_EXISTS = 'O E-mail informado já existe no sistema.';
    case PHONE_ALREADY_EXISTS = 'O número de telefone informado já existe no sistema.';
    case INVALID_CURRENT_PASSWORD = 'Senha atual inválida';
    case PERSON_NOT_FOUND = 'Registro Pessoa não encontrado.';

    // Products
    case THEME_HAS_CATEGORIES_IN_DELETE = 'Não é possível excluir um tema com categorias vinculadas a ele.';
    case CATEGORY_HAS_PRODUCTS_IN_DELETE = 'Não é possível excluir uma categoria com produtos vinculados a ela.';
    case THEME_NOT_FOUND = 'Tema não encontrado';
    case CATEGORY_NOT_FOUND = 'Categoria não encontrada';
    case CATEGORY_INVALID_UUID = 'A categoria informada deve ser um uuid válido.';
    case PRODUCT_DESCRIPTION_ALREADY_EXISTS = 'Já existe um produto cadastrado com este nome.';
    case FAVORITE_PRODUCT_ALREADY_EXISTS = 'Este produto já está cadstrado na sua lista de favoritos.';
    case PRODUCT_NOT_FOUND = 'Produto não encontrado ou inativo.';

    // Events
    case EVENT_NOT_FOUND = 'Evento não encontrado.';
    case EVENT_HAS_PRODUCTS_IN_DELETE = 'Não é possível excluir um evento com produtos vinculados a ele.';
    case EVENT_INVALID_UUID = 'O evento informado deve ser um uuid válido.';

    // City
    case CITY_NOT_FOUND = 'Cidade não encontrada.';
    case INVALID_UF = 'O campo UF é inválido.';

    // Zip Code
    case ADDRESS_NOT_FOUND = 'Não foram encontrados dados com o CEP informado.';

    // IMAGES
    case PRODUCT_IMAGE_ALREADY_EXISTS = 'Já existe uma imagem vinculada a este produto.';
    case IMAGE_NOT_FOUND = 'Imagem não encontrada.';
    case IMAGE_DOES_NOT_BELONG_TO_THE_PRODUCT = 'Esta imagem não pertece ao produto informado.';
}
