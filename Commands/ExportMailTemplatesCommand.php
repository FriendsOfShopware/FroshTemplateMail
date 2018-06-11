<?php

namespace FroshTemplateMail\Commands;

use Shopware\Commands\ShopwareCommand;
use Shopware\Models\Mail\Mail;
use Shopware\Models\Shop\Shop;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ExportMailTemplatesCommand extends ShopwareCommand
{
    protected function configure()
    {
        $this
            ->setName('frosh:export:mails')
            ->setDescription('Exports mail templates from backend to a given folder')
            ->addArgument('path', InputArgument::REQUIRED, 'Folder');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $folder = $input->getArgument('path');
        $translation = $this->container->get('translation');

        if (!file_exists($folder)) {
            mkdir($folder, 777, true);
        }

        $mailTemplates = $this->container->get('models')->getRepository(Mail::class)->findAll();
        $shops = $this->container->get('models')->getRepository(Shop::class)->findAll();

        /** @var Mail $item */
        foreach ($mailTemplates as $item) {
            file_put_contents($folder . '/' . sprintf('%s.subject.tpl', $item->getName()), $item->getSubject());
            file_put_contents($folder . '/' . sprintf('%s.html.tpl', $item->getName()), $item->getContentHtml());
            file_put_contents($folder . '/' . sprintf('%s.text.tpl', $item->getName()), $item->getContent());

            /** @var Shop $shop */
            foreach ($shops as $shop) {
                $translationArray = $translation->read($shop->getId(), 'config_mails', $item->getId());

                if (!empty($translationArray)) {
                    if (!empty($translationArray['content'])) {
                        file_put_contents($folder . '/' . sprintf('%s-%d.text.tpl', $item->getName(), $shop->getId()), $translationArray['content']);
                    }

                    if (!empty($translationArray['contentHtml'])) {
                        file_put_contents($folder . '/' . sprintf('%s-%d.html.tpl', $item->getName(), $shop->getId()), $translationArray['contentHtml']);
                    }

                    if (!empty($translationArray['subject'])) {
                        file_put_contents($folder . '/' . sprintf('%s-%d.subject.tpl', $item->getName(), $shop->getId()), $translationArray['subject']);
                    }
                }
            }
        }

        $io = new SymfonyStyle($input, $output);
        $io->success(sprintf('Exported %d mail templates from database to "%s"', count($mailTemplates), realpath($folder)));
    }
}