<?php
namespace ImportCustomers\CustomCmd\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use ImportCustomers\CustomCmd\Model\DataFactory;

class ImportData extends Command
{ 
    protected $dataFactory;

    public function __construct(
            DataFactory $dataFactory)
    {
        $this->dataFactory = $dataFactory;
        parent::__construct();      
        
    }
    
    const profile = 'profile';

    protected function configure()
    {
        //In commandline to get profile options
        $options = [
			new InputOption(
				self::profile,
				'-p',
				InputOption::VALUE_REQUIRED,
				'provide csv or json format'
			)
		];
        $this->setName('customer:importer')
            ->setDescription('Import data of customer')
            ->setDefinition($options)
            ->addArgument('source', InputArgument::REQUIRED, 'Source file'); //source argument
        
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $profile = $input->getOption(self::profile);
        $source = $input->getArgument('source');

        if ($profile === 'csv') {
            $csvData= $this->readCSV($source, $output);
            foreach ($csvData as $data) {
                $this->saveCustomerToDatabase($data, $profile);
            }
        } 
        elseif ($profile === 'json') {
            $jsonData = $this->readJSON($source, $output);
            foreach ($jsonData as $data) {
                $this->saveCustomerToDatabase($data, $profile);
            }
        } 

        else {
            $output->writeln('<error>Invalid profile format. Please use "csv" or "json".</error>');
            return 1; // Return a non-zero value to indicate an error.
        }

        return 0; // Return 0 to indicate a successful execution.
    }

    private function readCSV($csvFile, OutputInterface $output)
    {
        //To read csv file
        $data = [];
        if (($handle = fopen($csvFile, 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }
        if($data == null)
        {
            $output->writeln("<error>Error reading CSV file.</error>");
            return 1;
        }
        $output->writeln("<info>Data loaded successfully.</info>");
        return $data;
    }

    private function readJSON($jsonFile, OutputInterface $output)
    {
        $jsonContent = file_get_contents($jsonFile);
        $data= json_decode($jsonContent, true);
        if($data == null)
        {
            $output->writeln("<error>Error reading json file.</error>");
            return 1;
        }
        $output->writeln("<info>Data loaded successfully.</info>");
        return $data;
    }

    protected function saveCustomerToDatabase($data, $profile)
    {
        //using model saving the readed file in the table
        if($profile === 'json'){
            $mappedData = [
                'firstname' => $data['fname'],
                'lastname' => $data['lname'],
                'email' => $data['emailaddress'],
            ];
        }
        else
        {
            $mappedData = [
                'firstname' => $data['0'],
                'lastname' => $data['1'],
                'email' => $data['2'],
            ];
        }
        $customerModel = $this->dataFactory->create();
        $customerModel->setData($mappedData);
        $customerModel->save();
    }
}