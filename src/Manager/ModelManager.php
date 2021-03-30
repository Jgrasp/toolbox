<?php

namespace Jgrasp\Toolkit\Manager;

use Db;
use Jgrasp\Toolkit\Sql\ModelBuilder;
use ObjectModel;

class ModelManager
{
    private $models;

    private $deleteOnUninstall;

    public function __construct()
    {
        $this->models = [];
        $this->deleteOnUninstall = false;
    }

    public function addEntity(string $model): self
    {
        if (!in_array($model, $this->models, true)) {
            $this->models[] = $model;
        }

        return $this;
    }

    public function install(): bool
    {
        foreach ($this->models as $model) {
            $query = (new ModelBuilder($model))->getInstallSql();

            if (!Db::getInstance()->execute($query)) {
                return false;
            }

        }

        return true;
    }

    public function uninstall(): bool
    {
        if ($this->deleteOnUninstall) {
            foreach ($this->models as $model) {
                $query = (new ModelBuilder($model))->getUninstallSql();

                if (!Db::getInstance()->execute($query)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function getDeleteOnUninstall(): bool
    {
        return $this->deleteOnUninstall;
    }

    public function setDeleteOnUninstall(bool $deleteOnUninstall): ModelManager
    {
        $this->deleteOnUninstall = $deleteOnUninstall;
        return $this;
    }


}