drop table if exists task;
create table task (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name varchar(30),
    description varchar(30),
    user varchar(30),
    created varchar(30),
    completed boolean not null default false
);

drop table if exists my_user;
create table my_user (
    id int auto_increment primary key,
    login varchar(30) not null,
    name varchar(30),
    password varchar(30) not null
);