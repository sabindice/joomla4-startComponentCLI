
# Joomla 4 CLI component convertor

This is a CLI in PHP that will convert a Joomla 3 component to Joomla 4




## Run Locally

Clone the project or download the latest version

```bash
  git clone https://github.com/sabindice/joomla4-startComponentCLI
```

Go to the project directory find copyFile.php and replace this lines:

```bash
    $companyNameSpace = 'StartCompany';
    $componentName    = 'componentName'; //like content/contact/users
    $sourcePath       = '/Users/USER/DEVELOPER/project/components/';
```

After you replace them you run

```bash
  php copyFile.php
```

and wait for the script to finish.