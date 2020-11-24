<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MultiformEvent extends Component
{
    use WithFileUploads;

    public $photo;

    // informações básicas
    public $title = '';
    public $video_platform = 'youtube';
    public $video_id = '';
    // public $dtTranslations = Lang::get('adminlte::datatables');


    // Apresentadores e colaboradores
    public $participants = array(
        array('full_name' => '',
              'email' => '',
              'role' => '',
              'photo' => '',
              )
        );

    public $step = 0;

    private $stepActions = [
        'next1',
        'next2',
        'next3',
        'submit'
    ];

    // Guarda a sessão que o usuário está em cada passo
    public $steps_active_session = [
        'information' => 'basic',
        'scheduler' => '',
    ];

    protected $listeners = [
        'activeSession' => 'setActiveSession',
        'fileUpload' => 'handleFileUpload'
    ];

    public function render()
    {
        return view('livewire.multiform-event');
    }

    public function mount() {
        $this->participants[0]['full_name'] = Auth::user()->name;
        $this->participants[0]['email'] = Auth::user()->email;
        $this->participants[0]['role'] = 'presenter';
        // Primeiro verificamos se há algum valor antigo para os elementos do formulário que queremos renderizar.
        // Quando um usuário enviou um formulário com valores que não passaram na validação, exibimos esses valores antigos.
        // $this->participants = old('http_client_participants', $this->participants);
    }

    /**
     * controla a sessão ativa em cada passo
     */
    public function setActiveSession($session) {
        $this->steps_active_session[$this->step] = $session;
    }

    public function jumpToStep($step) {
        $this->step = $step;
    }

    public function decreaseStep() {
        $this->step--;
    }

    public function submit() {
        $this->validate([
            'title' => 'required|min:4',
            'video_id' => 'required',
        ]);

        $this->step++;

        // $action = $this->stepActions[$this->step];
        // $this->$action();
    }

    public function next() {
        $action = $this->stepActions[$this->step];
        $this->$action();
    }

    public function next1() {
        $this->validate([
            'title' => 'required|min:4',
            'video_id' => 'required',
        ]);

        $this->step++;
    }

    public function next2() {
        $this->step++;
    }

    public function next3() {
        $this->step++;
    }

    /**
     * Esta função irá adicionar um par de valores de participante vazio
     * fazendo com que uma linha extra seja renderizada.
     */
    public function addParticipant(): void
    {
        if (! $this->canAddMoreParticipants()) {
            return;
        }

        // cria uma chave autoincrement virtual
        // serve apenas para garantir um valor único para wire.model="$participant.{{ $seq_id }}.seq_id"
        // $seq_id = (count($this->participants) > 0) ? (end($this->participants)['seq_id'] + 1) : 0;
        array_push($this->participants, array('full_name' => '',
                                              'email' => '',
                                              'role' => '',
                                              'photo' => '',
                                            )
        );
    }

    /**
     * Aqui, removeremos o item com a chave fornecida
     * da matriz de participantes, então uma linha renderizada desaparece.
     */
    public function removeParticipant(int $i): void
    {
        unset($this->participants[$i]);

        // array_values — Retorna todos os valores de um array
        $this->participants = array_values($this->participants);
    }

    /**
     * Verifica se pode adicionar um novo participante
     */
    public function canAddMoreParticipants(): bool
    {
        return count($this->participants) < 6;
    }

    /**
     * Listiner que recebe a imagem enviada
     */
    function handleFileUpload($id, $file) {
        $this->resetErrorBag();
        $validator = Validator::make(
            [
                'file' => $file
            ],
            [
                'file' => 'base64image|base64max:100|base64dimensions:max_width=300,max_height=300'
            ],
            [
                'file.base64image' => 'The file must be an image (jpeg, png, bmp, gif, svg, or webp)',
                'file.base64max' => 'The Max size for file is :max kb',
                'file.base64dimensions' => 'The Max dimension for :attribute is :width X :height (width X height)',
            ]
        );

        if (!$validator->fails()) {
            $this->resetErrorBag('file');
            return $this->participants[$id]['photo'] = $file;
        }

        foreach ($validator->getMessageBag()->getMessages()['file'] as $message) {
            $this->addError('participants.' . $id . '.photo', $message);
        };
    }

}
