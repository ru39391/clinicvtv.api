<?php

namespace Zoomx\Controllers\Common;

class Constants
{
  const FORM_ERROR = 'Необходимо заполнить обязательные поля';
  const COMMON_ERROR = 'Невозможно выполнить запрос';

  const ID_KEY = 'id';
  const NAME_KEY = 'name';
  const CREATEDON_KEY = 'createdAt';
  const UPDATEDON_KEY = 'updatedAt';
  const RATING_KEY = 'rating';
  const AFTER_PIC_KEY = 'img_after';
  const BEFORE_PIC_KEY = 'img_before';
  const DEPT_ID_KEY = 'dept_id';
  const SPEC_ID_KEY = 'spec_id';
  const INTROTEXT_KEY = 'introtext';
  const IS_ACTIVE_KEY = 'active';
  const IS_HIDDEN_KEY = 'is_hidden';

  const ALLOWED_ORIGINS = [
    'http://localhost:5173',
    'http://localhost:3000',
    'http://127.0.0.1:5173'
  ];
}
