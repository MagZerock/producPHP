<?php

define('MONGODB_URI', getenv('MONGODB_URI') ?: getenv('SPRING_DATA_MONGODB_URI') ?: "mongodb+srv://admin:admin@awd.ypmipjt.mongodb.net/Products?retryWrites=true&w=majority");

define('DB_NAME', 'Products');
define('COLLECTION_NAME', 'products');
