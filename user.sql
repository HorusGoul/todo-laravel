create database todolaravel;
CREATE USER 'todolaravel'@'%' IDENTIFIED BY 'todolaravel';
GRANT ALL PRIVILEGES ON todolaravel.* TO 'todolaravel'@'%';