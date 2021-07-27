# Software-calculator.
##Advanced Software calculator using PHP Symfony (CLI the Rest APIs)- implementing OOP and solid principles
State-of-the-art software calculator.  developing a calculator for a company that is working on developing a mega operating system for personal computers in order to
include it in their next operator system release.

See the Project running on http://www.calcuc.com
## Goals and Features
Here are the  goals for the calculator:
1. Perform basic calculations (add, subtract, multiple, divide).
2. Perform square root, cubic root, power, and factorial calculations.
3. In order to eliminate any potential bugs in the software, automated tests must be written
and run to ensure the integrity of the application.
4. A report has to be pulled out daily/weekly/monthly basis showing the operations that have
been performed during that time period.
5. The calculator will be triggered and interacted with on the CLI.
6. The calculator will also be triggered via a web API.
7. PHP Symfony , MYSQL, REDIS, Vue.js are Used

## Getting start

See the project dashboard :
`http://www.calcuc.com  `
Api documentation Guide: 
`http://www.calcuc.com/Documentation/v1/API`
Download Postman collection:
`http://www.calcuc.com/Documentation/v1/download-postmanColloction`
CLI Guide: 
`http://www.calcuc.com/Documentation/v1/CLI`

## install and Setup
Get the App up and running in 3 simple steps

## i. Prepare the environment

### 1. Install PHP/MySQL

   See the guide to install and run PHP/MySQL based on your operation system
`https://www.php.net/manual/en/install.php`

#### To configure mysql on your linux 

   Check if root is having auth_socket plugin then run below command:

   mysql> SELECT User, Host, authentication_string,plugin  FROM mysql.user;

   mysql> UPDATE user SET plugin='mysql_native_password' WHERE User='root';

   mysql> FLUSH PRIVILEGES;

   mysql> exit;
### 2. Install composer
   See the guide on getcomposer
`https://getcomposer.org/doc/00-intro.md`

### 3. Install git
   See the guide on git-scm
`https://git-scm.com/book/en/v2/Getting-Started-Installing-Git`


## ii. Install Symfony and get the project

### 1.install symfony cli
on linux Use command: 

`wget https://get.symfony.com/cli/installer -O - | bash`

For other OS systems see symfony download
`https://symfony.com/download`


### 2. Use git clone to get the project
   Use git clone to get our software calculator using url https://github.com/omerahmed41/Software-calculator.git


### 3.Composer install
In command line head to project dir and type 

`composer install`



## iii. Configure MySql and run


### 1. Configure the symfony env

   Configure the symfony env on .env file and 
   change the `DATABASE_URL` to your Database user

DATABASE_URL="mysql://root:@localhost:3306/symfony"


### 2. Create Database using CLi

   Create database and make sure our setup is working using
   `php bin/console doctrine:database:create`


### 3. Doctrine:migrations

   Do doctrine:migrations using the CLI type 
  ` php bin/console doctrine:migrations:migrate`


### 4. Server start

Start the server using the CLI type:
`symfony server:start`




## iv. TODO

Use Docker to configure the env