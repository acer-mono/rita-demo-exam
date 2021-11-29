create table application
(
    id bigint auto_increment,
    author_id bigint not null,
    resolver_id bigint not null,
    category_id int not null,
    title varchar(512) not null,
    description text not null,
    photo_before text not null,
    photo_after text null,
    created_at datetime not null,
    updated_at datetime not null,
    constraint application_pk
        primary key (id),
    constraint application_author_fk
        foreign key (author_id) references user (id),
    constraint application_category_fk
        foreign key (category_id) references application_category (id),
    constraint application_resolver_fk
        foreign key (resolver_id) references user (id)
);