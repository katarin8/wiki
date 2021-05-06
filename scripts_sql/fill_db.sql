/*Country*/
INSERT INTO Country(Name) VALUES ("Russia");
INSERT INTO Country(Name) VALUES ("USA");
INSERT INTO Country(Name) VALUES ("France");
INSERT INTO Country(Name) VALUES ("Ukraine");
INSERT INTO Country(Name) VALUES ("Kazachstan");
INSERT INTO Country(Name) VALUES ("China");
INSERT INTO Country(Name) VALUES ("Japanese");

/*Subject*/
INSERT INTO Subject(Name, Description) VALUES ("Учебная статья", "Предназначена для получения студентами определённых знаний");
INSERT INTO Subject(Name, Description) VALUES ("Биологическая статья", "Предназначена для получения студентами определённых знаний");
INSERT INTO Subject(Name, Description) VALUES ("Физическая статья", "Предназначена для получения студентами определённых знаний");
INSERT INTO Subject(Name, Description) VALUES ("IT статья", "Предназначена для получения студентами определённых знаний");

/*User*/
INSERT INTO User(Login, PASSWORD, FIO, ID_Country,	State, Dith_Day, SocNet, Mail, Photo) VALUES ("123","123456","Приветов Привет Приветович",1,TRUE, '2000-06-05', "vk.com | facebook.com", "article@mail.ru", "photo");
INSERT INTO User(Login, PASSWORD, FIO, ID_Country,	State, Dith_Day, SocNet, Mail, Photo) VALUES ("1234","123456","Приветов Привет Приветович",1,TRUE, '2000-06-05', "vk.com | facebook.com", "article@mail.ru", "photo");
INSERT INTO User(Login, PASSWORD, FIO, ID_Country,	State, Dith_Day, SocNet, Mail, Photo) VALUES ("12345","123456","Приветов Привет Приветович",1,TRUE, '2000-06-05', "vk.com | facebook.com", "article@mail.ru", "photo");
INSERT INTO User(Login, PASSWORD, FIO, ID_Country,	State, Dith_Day, SocNet, Mail, Photo) VALUES ("123456","123456","Приветов Привет Приветович",1,TRUE, '2000-06-05', "vk.com | facebook.com", "article@mail.ru", "photo");
INSERT INTO User(Login, PASSWORD, FIO, ID_Country,	State, Dith_Day, SocNet, Mail, Photo) VALUES ("1234567","123456","Приветов Привет Приветович",1,TRUE, '2000-06-05', "vk.com | facebook.com", "article@mail.ru", "photo");


/*article*/
INSERT INTO Article(NAME, ID_Subject, DATE, Short_story, Rating, Media, ID_User) VALUES ("ФКН", 1, '2021-06-05', "Рассказ о том, что такое ФКН, Who is the best?", 4.5, "media", 1);

/*Block*/
INSERT INTO Block(Name,	TEXT,	ID_Article, ID_User) VALUES ("Начало","ФКН был образова там-то там-то, когда-то тогда-то",1,1);
INSERT INTO Block(Name,	TEXT,	ID_Article, ID_User) VALUES ("Расцвет","ФКН уже 20 лет работает в полную силу, что же будет дальше",1,1);
INSERT INTO Block(Name,	TEXT,	ID_Article, ID_User) VALUES ("Конец","Дополнят наши потомки :)",1,1);

COMMIT;