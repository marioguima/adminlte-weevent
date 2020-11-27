<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MultiformEvent extends Component
{
    use WithFileUploads;

    // informações básicas
    public $title = '';

    // apresentadores e colaboradores
    public $participants = array(
        array('full_name' => '',
              'email' => '',
              'role' => '',
              'photo' => '',
              )
    );

    // transmissão
    public $video_platform = '';
    public $video_id = '';

    // valores antigos/anterirores
    // para voltar quando clicar em cancelar
    public $old_values = [
        'information' => [
            'basic' => [
                'title' => '',
            ],
            'participants' => [],
            'transmission' => [
                'video_platform' => '',
                'video_id' => '',
            ],
        ],
    ];

    public $step = 0;

    public $steps_active_session = [
        'information' => 'basic',
        'cta' => '',
        'schedules' => '',
    ];

    public $stepsValidated = [
        'information' => [],
        'cta' => [],
        'schedules' => [],
    ];

    private $stepActions = [
        'next1',
        'next2',
        'next3',
        'submit'
    ];

    protected $listeners = [
        'fileUpload' => 'handleFileUpload'
    ];

    protected $rulesStepsSessions = [
        'information' => [
            'basic' => [
                'title' => 'required|min:4',
            ],
            'participants' => [
                'participants.*.full_name' => 'required',
                'participants.*.email' => 'required',
                'participants.*.role' => 'required',
                'participants.*.photo' => 'required',
            ],
            'transmission' => [
                'video_id' => 'required',
            ],
        ],
    ];

    public function render()
    {
        return view('livewire.multiform-event');
    }

    public function mount() {
        // participants
        $this->participants[0]['full_name'] = Auth::user()->name;
        $this->participants[0]['email'] = Auth::user()->email;
        $this->participants[0]['role'] = 'presenter';

        $this->old_values['information']['participants'] = $this->participants;

        // video platform
        $this->video_platform = 'youtube';
        $this->old_values['information']['transmission']['video_platform'] = $this->video_platform;
    }

    public function jumpToStep($step) {
        $this->step = $step;
    }

    public function decreaseStep() {
        $this->step--;
    }

    public function cancelEdit($stepName) {
        $this->steps_active_session[$stepName] = '';
    }

    public function cancelEditBasic() {
        $this->cancelEdit('information');
        $this->title = $this->old_values['information']['basic']['title'];
    }

    public function cancelEditParticipants() {
        $this->cancelEdit('information');
        $this->participants = $this->old_values['information']['participants'];
    }

    public function cancelEditTransmission() {
        $this->cancelEdit('information');
        $this->video_platform = $this->old_values['information']['transmission']['video_platform'];
        $this->video_id = $this->old_values['information']['transmission']['video_id'];
    }

    public function validateSessionStep($stepName, $sessionName) {
        $this->stepsValidated[$stepName] = array_diff($this->stepsValidated[$stepName], array($sessionName));

        $this->validate($this->rulesStepsSessions[$stepName][$sessionName],
            [],
            [
                'participants.*.full_name' => strtolower(trans('adminlte::weevent.full_name')),
                'participants.*.email' => trans('adminlte::weevent.email'),
                'participants.*.role' => trans('adminlte::weevent.role'),
                'participants.*.photo' => trans('adminlte::weevent.photo'),
            ]
        );

        array_push($this->stepsValidated[$stepName], $sessionName);
        $this->steps_active_session[$stepName] = '';
    }

    public function validateInformationBasic() {
        $this->validateSessionStep('information', 'basic');
        $this->old_values['information']['basic']['title'] = $this->title;
    }

    public function validateInformationParticipants() {
        $this->validateSessionStep('information', 'participants');
        $this->old_values['information']['participants'] = $this->participants;
    }

    public function validateInformationTransmission() {
        $this->validateSessionStep('information', 'transmission');
        $this->old_values['information']['transmission']['video_platform'] = $this->video_platform;
        $this->old_values['information']['transmission']['video_id'] = $this->video_id;
    }

    public function submit() {
        $this->validateInformation();

        // $action = $this->stepActions[$this->step];
        // $this->$action();
    }

    public function next() {
        $action = $this->stepActions[$this->step];
        $this->$action();
    }

    public function next1() {
        if(count($this->stepsValidated['information']) == 3) {
            $this->step++;
        }
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
        $validator = Validator::make(
            [
                'file' => $file
            ],
            [
                'file' => 'base64image|base64max:500|base64dimensions:max_width=300,max_height=300'
            ],
            [
                'file.base64image' => 'Only image',
                'file.base64max' => 'Max size 500kb',
                'file.base64dimensions' => 'Max dimension 300x300',
            ]
        );

        if (!$validator->fails()) {
            $this->resetErrorBag('participants.' . $id . '.photo');
            return $this->participants[$id]['photo'] = $file;
        }

        // para remover a photo anterior se a nova enviada não for válida
        $this->participants[$id]['photo'] = "";

        // remove os erros anteriores
        $this->resetErrorBag('participants.' . $id . '.photo');

        // adiciona os erros de validação
        foreach ($validator->getMessageBag()->getMessages()['file'] as $message) {
            $this->addError('participants.' . $id . '.photo', $message);
        };
    }

}
