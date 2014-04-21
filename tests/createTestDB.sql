CREATE DATABASE dump_store_tests;
CREATE USER 'dstore_tester'@'localhost' IDENTIFIED BY 'mysql_is_evil';
GRANT ALL PRIVILEGES ON dump_store_tests.* TO 'dstore_tester'@'localhost';