ETA-24hrs

# STEP 1:
- Creating a custom module to create a custom command that should get the file format and file path.
- Adding profile option using InputOption and argument using InputArgument.
- Eg: bin/magento customer:importer --profile='csv' sample.csv
- For now we are using only two file formats- csv and json.

# STEP 2:
- To read customer data in the csv and json files, we use two classes
- Magento\Framework\Filesystem\Io\File for json and
- Magento\Framework\File\Csv for csv files
- Retrieve the values of the profile and source options/arguments.
- Based on the profile option, determine whether to read a CSV or JSON file.
- And then turn it into an array, and validate the file input

# STEP 3:
- Push that data into the customer_entity table.

# STEP 4:
- Display the customer data in the admin panel 
- Customer->all customers.

