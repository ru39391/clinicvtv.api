<?php

namespace Zoomx\Controllers\Common;

class Constants
{
  const FORM_ERROR = 'Необходимо заполнить обязательные поля';
  const COMMON_ERROR = 'Невозможно выполнить запрос';

  const ID_KEY = 'id';
  const NAME_KEY = 'name';
  const IS_ACTIVE_KEY = 'active';
  const CREATEDON_KEY = 'createdAt';
  const UPDATEDON_KEY = 'updatedAt';
  const RATING_KEY = 'rating';

  const WORKFLOW_KEYS = [
    Constants::NAME_KEY,
    Constants::DATA_KEY,
    Constants::IS_ACTIVE_KEY,
    Constants::CREATEDON_KEY
  ];

  const ALLOWED_ORIGINS = [
    'http://localhost:5173',
    'http://localhost:3000',
    'http://127.0.0.1:5173'
  ];
}
