Contexto Sistema PHP → Laravel

  Stack actual

  - PHP MVC sin framework, MySQL PDO, WooCommerce REST API v3
  - Dominio: gestion-clandent.desarrollo.hostingsistemas.cl
  - DB: gest_clanv2 (MariaDB 10.11)

  ---
  Base de datos (tablas exactas en producción)

  users: id, username, email, password(bcrypt), full_name, role(admin|user), is_active, last_login, created_at, updated_at

  products: id, name, slug(unique), short_description, description, regular_price, sale_price, sku(unique), brand, custom_tags(text), status(draft|publish|...),
  type(simple|variable), wc_product_id, created_by(FK users), internal_observation, created_at, updated_at, wc_status_checked_at, wc_publication_status

  product_images: id, product_id(FK cascade), image_path, is_primary, sort_order, wc_image_id, created_at

  product_attributes: id, product_id(FK cascade), name, value, wc_attribute_id, wc_term_id(NULL=custom attr), created_at, updated_at

  product_variations: id, product_id(FK cascade), sku(unique), regular_price, sale_price, image_path, wc_variation_id, is_default, created_at, updated_at

  product_variation_attributes: id, variation_id(FK cascade), attribute_name, term_name, wc_attribute_id, wc_term_id, created_at, updated_at

  product_tags_map: id, product_id(FK cascade), tag_id, tag_name, is_custom(bool), created_at
    UNIQUE(product_id, tag_id)

  product_categories_map: id, product_id(FK cascade), category_id, wc_category_id

  activity_logs: id, user_id(FK cascade), user_name, action, entity_type, entity_id, entity_name, details(text), ip_address, created_at

  export_logs: id, product_id(nullable), product_name, user_id(nullable), user_name, status(exitoso|fallido), error_msg, attempts(tinyint), wc_product_id,
  created_at

  product_versions: id, product_id, version_number, name, slug, short_description, description, regular_price, sale_price, sku, brand, status, type, custom_tags,
  internal_observation, categories_json, attributes_json, images_json, variations_json, wc_tags_json, change_type, change_description, created_by, created_at

  Datos existentes: 181 productos, 6 usuarios, passwords bcrypt $2y$10$... compatibles con Hash::check()

  ---
  Routing (PHP index.php → Laravel equivalente)

  GET  /login              → AuthController::login()
  POST /auth/authenticate  → AuthController::authenticate()
  POST /auth/logout        → AuthController::logout()
  GET  /                   → HomeController::index()
  POST /home/sendFeedback  → HomeController::sendFeedback()

  GET  /products           → ProductsController::index()       (paginación, filtros: search, brand, status, type)
  GET  /products/create    → ProductsController::create()
  POST /products/store     → ProductsController::store()
  GET  /products/view/{id} → ProductsController::view()
  GET  /products/edit/{id} → ProductsController::edit()
  POST /products/update/{id}→ ProductsController::update()
  POST /products/duplicate/{id}→ ProductsController::duplicate()
  POST /products/createInWoocommerce/{id}→ ProductsController::createInWoocommerce()  ← exportar a WC
  POST /products/generateDescription→ ProductsController::generateDescription()  ← AJAX OpenAI
  POST /products/delete/{id}→ ProductsController::delete()
  GET  /products/logs      → ProductsController::logs()        (export_logs + activity_logs)
  POST /products/optimizeImages/{id}→ ProductsController::optimizeImages()
  GET  /products/checkWcStatus/{id}→ ProductsController::checkWcStatus()   ← AJAX
  POST /products/checkWcStatusBulk → ProductsController::checkWcStatusBulk() ← AJAX

  GET  /users              → UsersController (solo admin)
  CRUD /users/...          → UsersController (create/store/view/edit/update/delete/changePassword)

  ---
  Auth (sin Breeze — custom)
  
  - Session-based, $_SESSION['logged_in'], $_SESSION['user_id'], $_SESSION['role']
  - Middleware: autenticación para Products+Users, admin-only para Users
  - password_verify() → en Laravel: Hash::check()

  ---
  Variables de entorno necesarias

  DB_DATABASE=gest_clanv2
  DB_USERNAME=gest_clanv2
  DB_PASSWORD=***

  WC_STORE_URL=https://clandent.cl
  WC_CONSUMER_KEY=***
  WC_CONSUMER_SECRET=***
  WP_USERNAME=***
  WP_APP_PASSWORD=***   ← para taxonomías (marcas) via WP REST API

  IMAGIFY_API_KEY=***
  OPENAI_API_KEY=***    ← modelo: gpt-4o-mini

  MAIL_HOST=smtppro.zoho.com
  MAIL_PORT=587
  MAIL_USERNAME=alerta@tecnologicachile.cl
  MAIL_FROM_ADDRESS=alerta@tecnologicachile.cl

  ---
  Servicios críticos

  WooCommerceAPI — api/WooCommerceAPI.php:
  - Auth: consumer_key + consumer_secret en query string
  - Retry automático 3x con backoff exponencial (no reintenta 4xx)
  - Caché en api/cache/ (1 hora) para categorías/atributos/tags
  - Métodos: getCategories, getAttributes, getAttributeTerms, getTags, getProducts, getProduct, createProduct, updateProduct, createVariation, getVariations,
  getBrands, getOrCreateBrand, assignBrandToProduct
  - Usa WP REST API separado (/wp-json/) para marcas (taxonomía custom) con Basic Auth

  ImagifyService — cURL a https://app.imagify.io/api/upload/:
  - optimizeImage($path) → optimiza + descarga reemplazando original
  - optimizeProductImages($product) → itera todas las imágenes del producto y variaciones
  - Backup automático antes de reemplazar
  
  OpenAIService — gpt-4o-mini:
  - generateProductDescription($product_data) → retorna {short_description, description}
  - rewriteProductDescription($current, $product_data)
  - Respuesta parseada por regex (DESCRIPCIÓN CORTA: / DESCRIPCIÓN LARGA:)

  ---
  Lógica de negocio clave

  Exportar a WooCommerce (createInWoocommerce):
  1. Construir payload con imágenes, atributos, variaciones, categorías, tags
  2. Subir imágenes a WC por URL
  3. Si type=variable: crear producto → crear variaciones → asignar atributos a cada variación
  4. Guardar wc_product_id en DB
  5. Registrar en export_logs 

  Atributos custom vs WC: wc_term_id = NULL = atributo custom local; con valor = término de WooCommerce

  Caché estado WC: wc_publication_status + wc_status_checked_at en tabla products, TTL 1h, refresco vía AJAX (checkWcStatus)

  Imágenes: se suben localmente a assets/uploads/products/{id}/, luego se envían a WC por URL pública

  Product versions: snapshot JSON completo del producto en cada update (categorías, atributos, variaciones, imágenes como JSON)

  Activity logs: se registra create/edit/delete/export por usuario con IP

  ---
  Eso es todo lo importante. El laravel-pim/ ya tiene la estructura base — falta implementar createInWoocommerce, generateDescription, optimizeImages,
  checkWcStatus, y los form requests.