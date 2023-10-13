<?php

namespace App\Shared\Enums;

enum MessagesEnum: string {
    //General
    case METHOD_NOT_ALLOWED = 'Method not allowed.';
    case RESOURCE_NOT_FOUND = 'Resource not found.';
    case INTERNAL_SERVER_ERROR = 'Internal server error.';
    case UNAUTHORIZED = 'Unauthorized.';
    case NOT_FOUND = 'Not found.';
    case TOO_MANY_REQUESTS = 'Too Many Attempts.';
    case ACCESS_DENIED = 'Acesso negado a este recurso.';

    case REGISTER_NOT_FOUND = 'Registro não encontrado.';
    case INVALID_UUID = 'O valor enviado não é um Uuid válido.';
    case INVALID_EMAIL = 'O valor enviado não é um E-mail válido.';
    case INVALID_UNIQUE_NAME = 'O valor enviado não é um nome válido.';
    case NOT_AUTHORIZED = 'Você não tem permissão para acessar este recurso.';
    case MODULE_NOT_AUTHORIZED = 'Você não tem permissão para acessar este módulo.';
    case MUST_BE_AN_ARRAY = 'O campo deve ser um array.';
    case NOT_ENABLED = 'Você não tem permissão para acessar a plataforma. \n Para liberar ou verificar seu acesso entre em contato com o suporte.';

    // Sessions
    case LOGIN_ERROR = 'E-mail ou senha incorretos.';
    case INACTIVE_USER = 'Usuário não encontrado ou está inativo no sistema. Se necessário, entre em contato com o suporte.';
    case UNVERIFIED_EMAIL = 'E-mail não verificado.';
    case EMAIL_ALREADY_VERIFIED = 'Este usuário já teve seu e-mail verificado, se necessário entre em contato com o suporte.';
    case SUCCESS_MODIFY_PASSWORD = 'Senha redefinida com sucesso.';
    case INVALID_FORGOT_PASSWORD_CODE = 'Código de verificação expirado, por favor solicite uma nova troca de senha.';
    case INVALID_PROFILE = 'Perfil inválido.';
    case PASSWORD_CODE_NOT_FOUND = 'Código de verificação não encontrado.';

    // Users
    case USER_NOT_FOUND = 'Usuário não encontrado.';
    case CODE_NOT_FOUND = 'Código não encontrado.';
    case INVALID_CODE = 'Código inválido.';
    case PROFILE_NOT_FOUND = 'Perfil não encontrado.';
    case PROFILE_NOT_ALLOWED = 'Sem acesso ao perfil.';
    case EMAIL_ALREADY_EXISTS = 'O E-mail informado já existe no sistema.';
    case PHONE_ALREADY_EXISTS = 'O número de telefone informado já existe no sistema.';
    case INVALID_CURRENT_PASSWORD = 'Senha atual inválida';
    case PERSON_NOT_FOUND = 'Registro Pessoa não encontrado.';


    // City
    case CITY_NOT_FOUND = 'Cidade não encontrada.';
    case INVALID_UF = 'O campo UF é inválido.';

    // Zip Code
    case ADDRESS_NOT_FOUND = 'Não foram encontrados dados com o CEP informado.';

    // IMAGES
    case PRODUCT_IMAGE_ALREADY_EXISTS = 'Já existe uma imagem vinculada a este produto.';
    case IMAGE_NOT_FOUND = 'Imagem não encontrada.';
    case IMAGE_DOES_NOT_BELONG_TO_THE_PRODUCT = 'Esta imagem não pertece ao produto informado.';

    // MODULES - MEMBERSHIP
    case CHURCH_HAS_MEMBERS_IN_DELETE = 'Não é possível excluir uma igreja com membros vinculados.';
    case CHURCH_HAS_RESPONSIBLE_IN_DELETE = 'Não é possível excluir uma igreja com responsáveis vinculados.';
    case USER_CHURCH_RELATIONSHIP_NOT_FOUND = 'Este usuário não possui vínculo com esta igreja.';
    case USER_HAS_NO_CHURCH = 'Usuário não possui vínculo com nenhuma igreja.';
    case USER_PAYLOAD_HAS_NO_CHURCH = 'O usuário informado não possui vínculo com nenhuma igreja.';
    case NO_ACCESS_TO_CHURCH = 'Não é possível acessar/utilizar esta igreja.';
    case NO_ACCESS_TO_CHURCH_MEMBERS = 'Não é possível acessar informações de membros vinculados à outras igrejas.';
    case MODULE_NOT_ALLOWED = 'Sem acesso ao módulo.';
    case MEMBER_NOT_FOUND = 'Membro não encontrado.';
    case MODULE_NOT_FOUND = 'Módulo não encontrado.';
    case USER_HAS_NO_LINKED_MODULES = 'Usuário não possui módulos vinculados.';
    case INVALID_RESP_MEMBERS_QUANTITY = 'São permitidos apenas 3 membros responsáveis por igreja.';
    case INVALID_RESP_MEMBER_TYPE = 'O membro selecionado possui um tipo inválido.';
    case INVALID_RESP_MEMBER_PROFILE = 'Somente usuários com perfil Admin Igreja podem ser responsáveis por uma igreja.';
    case CHURCH_NOT_FOUND = 'Igreja não encontrada';

    // MODULES - STORE
    case CATEGORY_NOT_FOUND = 'Categoria não encontrada.';
    case CATEGORY_NAME_ALREADY_EXISTS = 'Já existe uma categoria com este nome. Escolha outro.';
    case CATEGORY_HAS_SUBCATEGORIES = 'Esta categoria possui subcategorias vinculadas a ela, remova estes vínculos e tente novamente.';

    case SUBCATEGORY_NOT_FOUND = 'Subcategoria não encontrada.';
    case SUBCATEGORY_NAME_ALREADY_EXISTS = 'Já existe uma subcategoria com este nome. Escolha outro.';
}
