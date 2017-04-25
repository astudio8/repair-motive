# repair-motive
Open Source automotive repair / reciept tracking.

This is my first ever open source software. It started out as a project, but I realized that this wasn't my passion to create auto-industry related apps.
I hope someone finds this useful or maybe turns it into something bigger than I did.

## Setting the project up

Repair-Motive uses Vagrant for the VM, Phinx for the DB migration, Gulp for the asset management, and Twig for the views. It's really simple
in concept.

### Step 1 (Vagrant Up)
Fork the repo and then clone it somewhere on your computer. Enter the directory and 'vagrant up'. You'll need something like VirtualBox to handle
your VM. I developed this on a Windows machine, and had to update my 'hosts' file to include the following IP / Host

*127.0.0.1 local.repairmotive.com*

The same may work for Mac users. 

### Step 2 (Bower, Composer, NPM)
Assuming step one went well and the command ran fine, you'll want to install the dependencies this project relies on:
- run: npm install
- run: bower install
- run: composer install

If all of these ran without any issues then you should now have all of the libs and dependencies. Nearly done!

### Step 3 (Phinx)
In order to install the DB and migrate, you'll need to run Phinx migrate. First, ensure that your /phinx.yml file is accurate with your DB settings.
Secondly, you'll need to SSH into your VM and navigate to repairmotive.com folder before executing the following command:

```php vendor/robmorgan/phinx/bin/phinx migrate```

### Step 3
You'll want to run Gulp. The project has a Gulpfile that utilize a watcher, so you wont need to keep running it every time you change the JS
or CSS. Everything get's minified into the dist/ directory under www/

### Step 4
If everything ran properly, you should be able to visit your browser and go to local.repairmotive.com:8888

Again, this is my first time writting up a guide or releasing any sort of software. Please feel free to email me at adam@alicki.me for help
setting up the project. I may eventually update this guide or the code to better help with the spinup.
