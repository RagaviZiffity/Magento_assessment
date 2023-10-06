<?php

namespace ImportCustomers\CustomCmd\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\Customer as ResourceModel;
use Magento\Framework\File\Csv;

class ImportData extends Command
{
    protected $csvProcessor;
    protected $customerRepository;
    protected $customerFactory;

    public function __construct(
        ResourceModel $customerRepository,
        CustomerFactory $customerFactory,
        Csv $csvProcessor
    ) {
        $this->csvProcessor = $csvProcessor;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
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
            $csvData = $this->csvProcessor->getData($source);
            if ($csvData == null) {
                $output->writeln("<error>Error reading CSV file.</error>");
                return 1;
            }
            $output->writeln("<info>Data loaded successfully.</info>");
            foreach ($csvData as $data) {
                $customer = $this->customerFactory->create();
                $customer->setFirstname($data['0']);
                $customer->setLastname($data['1']);
                $customer->setEmail($data['2']);
                $this->customerRepository->save($customer);
            }
            $output->writeln("<info>Customer imported successfully.</info>");
        } elseif ($profile === 'json') {
            $jsonData = $this->readJSON($source, $output);
            foreach ($jsonData as $data) {
                $customer = $this->customerFactory->create();
                $customer->setFirstname($data['fname']);
                $customer->setLastname($data['lname']);
                $customer->setEmail($data['emailaddress']);
                $this->customerRepository->save($customer);
            }
            $output->writeln("<info>Customer imported successfully.</info>");
        } else {
            $output->writeln('<error>Invalid profile format. Please use "csv" or "json".</error>');
            return 1; //Return a non-zero value to indicate an error.
        }

        return 0; //Return 0 to indicate a successful execution.
    }

    private function readJSON($jsonFile, OutputInterface $output)
    {
        $jsonContent = file_get_contents($jsonFile);
        $data = json_decode($jsonContent, true);
        if ($data == null) {
            $output->writeln("<error>Error reading json file.</error>");
            return 1;
        }
        $output->writeln("<info>Data loaded successfully.</info>");
        return $data;
    }
}
