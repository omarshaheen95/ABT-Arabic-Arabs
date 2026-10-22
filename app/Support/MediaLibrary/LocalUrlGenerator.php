<?php

namespace App\Support\MediaLibrary;

use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

/**
 * The URLs medialibrary 7 gave the files of a local disk: the disk's url
 * with the current site's root removed, so "/storage/2209/Grade8-A1.jpg"
 * instead of APP_URL."/storage/2209/Grade8-A1.jpg" as version 11 does. The
 * platform answers on more than one domain (see sysDomain()), and an absolute
 * URL built from APP_URL points the pages of the other domains at it.
 *
 * Only the file name is URL encoded, as in version 7. Other disks get the
 * version 11 URL.
 */
class LocalUrlGenerator extends DefaultUrlGenerator
{
    public function getUrl(): string
    {
        $disk = $this->getDiskName();
        $diskUrl = config("filesystems.disks.$disk.url");

        if (config("filesystems.disks.$disk.driver") !== 'local' || ! $diskUrl) {
            return parent::getUrl();
        }

        $url = str_replace(url('/'), '', $diskUrl).'/'.$this->getPathRelativeToRoot();

        if (DIRECTORY_SEPARATOR != '/') {
            $url = str_replace(DIRECTORY_SEPARATOR, '/', $url);
        }

        $url = pathinfo($url, PATHINFO_DIRNAME).'/'.rawurlencode(pathinfo($url, PATHINFO_BASENAME));

        return $this->versionUrl($url);
    }
}
