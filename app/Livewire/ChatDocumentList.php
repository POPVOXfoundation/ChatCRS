<?php

namespace App\Livewire;

use Livewire\Component;

class ChatDocumentList extends Component
{
    public $documents = [];
    public $activeDocumentId;

    public function mount($documents, $activeDocumentId)
    {
        $this->documents = $documents;
        $this->activeDocumentId = $activeDocumentId;
    }

    public function selectDocument($id)
    {
        $this->activeDocumentId = $id;
        $this->dispatch('documentSelected', id: $id);
    }

    public function render()
    {
        return view('livewire.chat-document-list', [
            'documents' => $this->documents,
            'activeDocumentId' => $this->activeDocumentId,
        ]);
    }

    public function updated($name, $value)
    {
        if ($name === 'documents' || $name === 'activeDocumentId') {
            $this->render();
        }
    }
}
