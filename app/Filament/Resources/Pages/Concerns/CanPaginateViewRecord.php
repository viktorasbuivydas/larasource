<?php

namespace App\Filament\Resources\Pages\Concerns;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Actions\NextAction;
use App\Filament\Resources\Actions\PreviousAction;

trait CanPaginateViewRecord
{
    protected function configureAction(Action $action): void
    {
        $this->configureActionRecord($action);

        match (true) {
            $action instanceof PreviousAction => $this->configurePreviousAction($action),
            $action instanceof NextAction => $this->configureNextAction($action),
            default => parent::configureAction($action),
        };
    }

    protected function configurePreviousAction(Action $action): void
    {
        if ($this->getPreviousRecord()) {
            $action->url(fn(): string => static::getResource()::getUrl(static::getResourcePageName(), ['record' => $this->getPreviousRecord()]));
        } else {
            $action
                ->disabled()
                ->color('gray');
        }
    }

    protected function configureNextAction(Action $action): void
    {
        if ($this->getNextRecord()) {
            $action->url(fn(): string => static::getResource()::getUrl(static::getResourcePageName(), ['record' => $this->getNextRecord()]));
        } else {
            $action
                ->disabled()
                ->color('gray');
        }
    }

    protected function getPreviousRecord(): ?Model
    {
        return $this
            ->getRecord()
            ->where('id', '<', $this->getRecord()->id)
            ->orderBy('id', 'desc')
            ->notApproved()
            ->first();
    }

    protected function getNextRecord(): ?Model
    {
        return $this
            ->getRecord()
            ->where('id', '>', $this->getRecord()->id)
            ->orderBy('id', 'asc')
            ->notApproved()
            ->first();
    }
}
