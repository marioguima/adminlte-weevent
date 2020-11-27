@push('css')
{{-- site de onde peguei o efeito de passar sobre as fotos --}}
{{-- https://codepen.io/dig-lopes/pen/WLVGda?editors=1100 --}}

<style>
  .label-step {
    color: #9e9e9e;
    font-size: 0.8rem;
  }

  .nav-pills .nav-link.active,
  .nav-pills .show>.nav-link {
    color: #fff;
    background-color: #26bdde;
  }

  .nav-pills .nav-link:not(.active):hover {
    color: #9e9e9e;
  }

  .nav-tabs-custom .nav-link {
    border-radius: 100%;
    /* width: 3.125rem;
    height: 3.125rem; */
    width: 2.5rem;
    height: 2.5rem;
    text-align: center;
    display: -webkit-box;
    display: flex;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    background: #fff;
    color: #9e9e9e;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 6px 0 rgba(0, 0, 0, .025);
    position: relative;
    font-size: 1.3125rem;
    line-height: 0.5;
  }

  .nav-tabs-custom .nav-link.active:before {
    -webkit-transition: opacity .3s ease-in-out;
    transition: opacity .3s ease-in-out;
    border-radius: 100%;
    content: "";
    /* width: 3.5rem;
    height: 3.5rem; */
    width: 2.9rem;
    height: 2.9rem;
    position: absolute;
    top: -.25rem;
    left: -.25rem;
    border-width: .25rem;
    border-style: solid;
    box-shadow: 0 0 10px 5px rgba(0, 0, 0, .075);
    opacity: 1;
    border-color: #fff;
  }

  /* botton line */
  .nav-tabs-custom .nav-item {
    position: relative;
  }

  .nav-justified .nav-item {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -ms-flex-positive: 1;
    flex-grow: 1;
    text-align: center;
  }

  .nav-tabs .nav-item {
    margin-bottom: -1px;
  }

  .nav-tabs .nav-item.show .nav-link,
  .nav-tabs .nav-link.active {
    color: #495057;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
  }

  .nav-tabs .nav-link,
  .nav-pills .nav-link {
    font-family: "Roboto", sans-serif;
    color: #1d1e3a;
    font-size: 14px;
  }

  .nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
  }

  .nav-tabs-custom>li>a {
    color: #1d1e3a;
  }

  .nav-link {
    display: block;
    padding: .5rem 1rem;
  }

  .nav-tabs-custom>li:hover:after {
    -webkit-transform: scale(1);
    transform: scale(1);
  }

  .nav-tabs-custom>li:after {
    content: "";
    background: #7fd3ec;
    height: 2px;
    position: absolute;
    width: 100%;
    left: 0;
    bottom: -7px;
    -webkit-transition: all 250ms ease 0s;
    transition: all 250ms ease 0s;
    -webkit-transform: scale(0);
    transform: scale(0);
  }

  /* upload file */
  .image-hover input[type='file'] {
    /* display: none; */
  }

  .image-hover {
    background: #dbdada;
    border-radius: 50%;
    color: #8b8989;
    transition: .5s;
  }

  .image-hover::before {
    background: #26bdde;
    transition: .5s;
    transform: scale(.9);
    /* z-index: -1; */
  }

  .image-hover:hover {
    color: #26bdde;
    box-shadow: 0 0 8px #26bdde;
    text-shadow: 0 0 2px #26bdde;
  }

</style>

<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
@endpush

<div class="row m-0 p-0">
  <div class="col m-0 p-0 pb-5">

    <ul class="nav nav-pills nav-tabs-custom nav-justified mt-n3" role="tablist">
      <li class="nav-item mr-1 mr-sm-3 d-flex flex-column align-items-center">
        <a class="nav-link @if($step == 0) active show @endif @if($step == 0) disabled @endif" wire:click="jumpToStep(0)" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false"><i class="fal fa-info"></i></a>

        <span class="label-step d-none d-sm-block">{{trans('adminlte::weevent.information')}}</span>
      </li>

      <li class="nav-item mr-1 mr-sm-3 d-flex flex-column align-items-center">
        <a class="nav-link @if($step == 1) active show @endif @if($step == 1 || sizeof($stepsValidated['information']) <> 3 || $steps_active_session['information'] != '') disabled @endif" wire:click="jumpToStep(1)" id="cta-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="fal fa-external-link"></i></a>
        <span class="label-step d-none d-sm-block">{{trans('adminlte::weevent.cta')}}</span>
      </li>

      <li class="nav-item d-flex flex-column align-items-center">
        <a class="nav-link @if($step == 2) active show @endif @if($step == 2 || sizeof($stepsValidated['information']) <> 3 || !$stepsValidated['cta'] || $steps_active_session['information'] != '') disabled  @endif" wire:click="jumpToStep(2)" id="schedules-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="true"><i class="fal fa-calendar-alt"></i></a>
        <span class="label-step d-none d-sm-block">{{trans('adminlte::weevent.schedules')}}</span>
      </li>
    </ul>

    {{-- <form id="frmEvent" method="POST" action="{{ route('event.store') }}">
    @csrf --}}
    <form wire:submit.prevent="submit" style="margin-top: 7px;" id="frmEvent">

      <div class="tab-content">
        <div class="tab-pane fade @if ($step == 0) active show @endif" id="info" role="tabpanel" aria-labelledby="info-tab">

          {{-- Information - Basic --}}
          {{-- <div class="card" id="cardStep_0_Basic"> --}}
          <div class="card @if ($step == 0 && $steps_active_session['information'] != 'basic') collapsed-card @endif" data-active-session="basic" id="cardStep_0_Basic">

            <!-- card-header (Basic) -->
            <div class="card-header without-border">
              @if($errors->has('title'))
              <h3 class="card-title text-danger">{{ trans('adminlte::weevent.basic') }} <span class="badge badge-danger">1</span></h3>
              @else
              <h3 class="card-title">{{ trans('adminlte::weevent.basic') }}</h3>
              @endif

              <!-- card-tools -->
              <div class="card-tools">
                @if($step == 0 && $steps_active_session['information'] != 'basic')
                {{-- verifica se a sessão basic é válida --}}
                @if(in_array('basic',$stepsValidated['information']))
                <span class="text-success">Configurada</span>
                @else
                <span class="text-danger">Não configurada</span>
                @endif
                @endif
                {{-- cancel / confirm --}}
                {{-- <button type="button" class="btn btn-default btn-sm mr-2" style="@if($step == 0 && $steps_active_session['information'] != 'basic') display: none; @endif" wire:click="cancelEdit('information', 'basic')" wire:loading.attr="disabled">Cancelar</button> --}}
                <button type="button" class="btn btn-default btn-sm mr-2" style="@if($step == 0 && $steps_active_session['information'] != 'basic') display: none; @endif" wire:click="$emit('cancelEdit')" wire:loading.attr="disabled">Cancelar</button>
                <button type="button" class="btn bg-success btn-sm" style="@if($step == 0 && $steps_active_session['information'] != 'basic') display: none; @endif" disabled wire:click="validateInformationBasic" wire:loading.attr="disabled">Confirmar</button>
                {{-- edit --}}
                <button type="button" class="btn btn-default btn-sm btn-tools-circle" style="@if($step == 0 && $steps_active_session['information'] == 'basic') display: none; @endif" data-card-widget="collapse" wire:loading.attr="disabled" @if ($step==0 && $steps_active_session['information'] !='' ) disabled @endif><i class="fal fa-pencil"></i></button>
              </div>
            </div>

            <!-- Title -->
            <div class="card-body pt-2 pb-2">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group label-floating is-empty">
                    <label>{{ trans('adminlte::weevent.title_of_event') }}</label>
                    <input id="inputTitle" value="{{ $title }}" wire:model.defer="title" type="text" class="form-control" placeholder="{{trans('adminlte::weevent.title_placeholder')}} ...">
                    @error('title')<small class="form-text text-danger">{{ $message }}</small>@enderror
                  </div>
                </div>
              </div>

            </div>

          </div>

          {{-- Information - Participants and collaborators --}}
          {{-- <div class="card collapsed-card" data-active-session="participants" id="cardStep_0_Participants"> --}}
          <div class="card @if ($step == 0 && $steps_active_session['information'] != 'participants') collapsed-card @endif" data-active-session="participants" id="cardStep_0_Participants">

            <!-- card-header (Presenters and collaborators) -->
            <div class="card-header without-border">
              @php
              $nParticipantsWithErrors=0;
              @endphp

              @php
              $nParticipantsWithErrors =
              count(preg_grep ('/^participants.\w*.full_name/', array_keys($errors->messages()))) +
              count(preg_grep ('/^participants.\w*.email/', array_keys($errors->messages()))) +
              count(preg_grep ('/^participants.\w*.role/', array_keys($errors->messages()))) +
              count(preg_grep ('/^participants.\w*.photo/', array_keys($errors->messages())))
              @endphp

              @if($nParticipantsWithErrors)
              <h3 class="card-title text-danger">{{ trans('adminlte::weevent.presenters_collaborators') }} <span class="badge badge-danger">{{$nParticipantsWithErrors}}</span></h3>
              @else
              <h3 class="card-title">{{ trans('adminlte::weevent.presenters_collaborators') }}</h3>
              @endif

              {{-- card-tools --}}
              <div class="card-tools">
                {{-- cancel / confirm --}}
                <button type="button" class="btn btn-default btn-sm mr-2" style="@if($step == 0 && $steps_active_session['information'] != 'participants') display: none; @endif" wire:click="cancelEdit('information', 'participants')" wire:loading.attr="disabled">Cancelar</button>
                <button type="button" class="btn bg-success btn-sm" style="@if($step == 0 && $steps_active_session['information'] != 'participants') display: none; @endif" disabled wire:click="validateInformationParticipants" wire:loading.attr="disabled">Confirmar</button>
                {{-- edit --}}
                <button type="button" class="btn btn-default btn-sm btn-tools-circle" style="@if($step == 0 && $steps_active_session['information'] == 'participants') display: none; @endif" data-card-widget="collapse" wire:loading.attr="disabled" @if ($step==0 && $steps_active_session['information'] !='' ) disabled @endif><i class="fal fa-pencil"></i></button>

                {{-- <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fal fa-plus"></i>
                </button> --}}
              </div>
            </div>

            {{-- List of participants --}}
            <div class="card-body pt-2 pb-2">

              {{-- cabeçalho --}}
              <div class="row mb-1 d-none d-sm-flex">
                <div class="col d-flex">
                  {{-- full name --}}
                  <div class="col-sm-3">
                    <label>{{ trans('adminlte::weevent.full_name') }}</label>
                  </div>

                  {{-- email --}}
                  <div class="col-sm-4">
                    <label>{{ trans('adminlte::weevent.email') }}</label>
                  </div>

                  {{-- role --}}
                  <div class="col-sm-3">
                    <label>{{ trans('adminlte::weevent.role') }}</label>
                  </div>

                  {{-- photo --}}
                  <div class="col-sm-1 d-flex justify-content-center">
                    <label>{{ trans('adminlte::weevent.photo') }}</label>
                  </div>

                  {{-- actions --}}
                  <div class="col-sm-1 order-sm-last" style="width: 50px">
                  </div>
                </div>

              </div>

              {{-- participants --}}
              @foreach ($participants as $participant)


              <div class="row" wire:key="post-field-{{ $loop->index }}">

                {{-- full name --}}
                <div class="col-4 d-block d-sm-none align-self-center">
                  <label>{{ trans('adminlte::weevent.full_name') }}:</label>
                </div>
                <div class="col-8 col-sm-3">
                  <div class="form-group">
                    <input type="text" wire:model.defer="participants.{{ $loop->index }}.full_name" class="form-control-sm w-100" placeholder="{{ @trans('adminlte::weevent.full_name_placeholder') }} ...">
                    @error("participants.{$loop->index}.full_name")<small class="form-text text-danger">{{ $errors->first("participants.{$loop->index}.full_name") }}</small>@enderror
                  </div>
                </div>

                {{-- email --}}
                <div class="col-4 d-block d-sm-none align-self-center">
                  <label>{{ trans('adminlte::weevent.email') }}:</label>
                </div>
                <div class="col-8 col-sm-4">
                  <div class="form-group">
                    <input type="text" wire:model.defer="participants.{{ $loop->index }}.email" class="form-control-sm w-100" placeholder="{{ @trans('adminlte::weevent.email_placeholder') }} ...">
                    @error("participants.{$loop->index}.email")<small class="form-text text-danger">{{ $errors->first("participants.{$loop->index}.email") }}</small>@enderror
                  </div>
                </div>

                {{-- role --}}
                <div class="col-8 col-sm-3">
                  <div class="form-row">
                    <div class="custom-control custom-radio mr-2">
                      <input wire:model.defer="participants.{{ $loop->index }}.role" class="custom-control-input" type="radio" id="role_presenter{{ $loop->index }}" name="role{{ $loop->index }}" value="presenter" checked="">
                      <label for="role_presenter{{ $loop->index }}" class="custom-control-label">{{ @trans('adminlte::weevent.presenter') }}</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input wire:model.defer="participants.{{ $loop->index }}.role" class="custom-control-input" type="radio" id="role_moderator{{ $loop->index }}" name="role{{ $loop->index }}" value="moderator" checked="">
                      <label for="role_moderator{{ $loop->index }}" class="custom-control-label">{{ @trans('adminlte::weevent.moderator') }}</label>
                    </div>
                    {{-- <input type="text" wire:model.defer="participants.{{ $loop->index }}.role" class="form-control" placeholder="{{ @trans('adminlte::weevent.role_placeholder') }} ..."> --}}
                    @error("participants.{$loop->index}.role")<small class="form-text text-danger">{{ $errors->first("participants.{$loop->index}.role") }}</small>@enderror
                  </div>
                </div>

                {{-- photo and actions --}}
                {{-- estão juntos para a mensagem de erro da photo aparecer abaixo sem quebrar --}}
                <div class="col-4 col-sm-2">
                  <style>
                    /* photo */
                    .center {
                      height: 100%;
                      display: flex;
                      align-items: center;
                      justify-content: center;
                    }

                    .form-input {
                      width: 40px;
                      height: 40px;
                    }

                    .form-input input {
                      display: none;
                    }

                    .form-input label {
                      display: block;
                      width: 100%;
                      height: 100%;
                      line-height: 50px;
                      text-align: center;
                      background-color: #333;
                      cursor: pointer;
                      color: #fff;
                      border-radius: 50%;
                    }

                    .preview {
                      position: absolute;
                      top: 0;
                      height: 34px;
                      width: 34px;
                      margin: auto;
                      background-size: cover;
                      border: solid 1px #d3d3d3;
                    }

                    .form-input img {
                      width: 100%;
                      height: 100%;
                      display: none;

                    }

                  </style>

                  <div class="form-row">
                    <div class="col-6">
                      <div class="center">
                        <div class="form-input">
                          <div id="profile-img-{{$loop->index}}-preview" class="d-flex justify-content-center preview image-hover" onclick="clickFileUpload({{$loop->index}})" style="background-image: url('{{$participants[$loop->index]['photo']}}')">
                            <i class="fal fa-user align-self-center @if($participants[$loop->index]['photo']) d-none @endif" id="icon-{{$loop->index}}-preview"></i>
                            <input type="file" id="input-file-{{$loop->index}}" wire:change="$emit('fileChoosen', '{{ $loop->index }}')" accept="image/*">
                          </div>
                        </div>
                      </div>

                    </div>

                    <div class="col-6">
                      <div class="row justify-content-md-center">
                        <div class="col-3">
                          <a href="#" wire:click.prevent="addParticipant()" class="btn p-0 btn-tool @if(!$this->canAddMoreParticipants()) disabled @endif"><i class="fal fa-plus"></i></a>
                        </div>
                        <div class="col-3">
                          @if ($loop->index > 0)
                          <a href="#" wire:click.prevent="removeParticipant({{ $loop->index }})" class="btn p-0 btn-tool"><i class="fal fa-minus"></i></a>
                          @endif
                        </div>
                      </div>
                    </div>
                    @error("participants.{$loop->index}.photo")<small class="form-text text-danger m-0 p-0">{{ $errors->first("participants.{$loop->index}.photo") }}</small>@enderror
                  </div>


                  {{-- transfere o click para o input file --}}
                  <script>
                    function clickFileUpload(id) {
                      inputFile = document.getElementById('input-file-' + id);
                      inputFile.click();
                    }

                  </script>
                </div>

                <hr class="mt-0 mb-3 p-0 col-sm-12 d-sm-none d-block">
              </div>

              @endforeach

            </div>
            <!-- /.card-body -->
          </div>

          {{-- Information - Transmission --}}
          <div class="card collapsed-card" data-active-session="transmission" id="cardStep_0_Transmission">

            <!-- card-header (Transmission) -->
            <div class="card-header without-border">
              @if($errors->has('video_id'))
              <h3 class="card-title text-danger">{{ trans('adminlte::weevent.transmission') }} <span class="badge badge-danger">1</span></h3>
              @else
              <h3 class="card-title">{{ trans('adminlte::weevent.transmission') }}</h3>
              @endif

              <!-- card-tools -->
              <div class="card-tools">
                {{-- <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fal @if ($step == 0 && $steps_active_session['information'] != 'transmission') fa-plus @else fa-minus @endif"></i> --}}
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fal fa-minus"></i>
                </button>
              </div>
            </div>

            {{-- <div class="card-body pt-1" style="display: @if ($step == 0 && $steps_active_session['information'] != 'transmission') none; @else block; @endif"> --}}
            <div class="card-body pt-2 pb-2">
              {{-- video platform --}}
              <div class="row">
                <div class="col-md-5 col-lg-4">
                  <div class="form-group">
                    <label for="">{{ trans('adminlte::weevent.video_platform') }}</label><br />
                    <div class="btn-group btn-group-toggle">
                      <label class="btn btn-info @if($video_platform=='youtube') active @endif">
                        <input type="radio" wire:model="video_platform" name="video_platform" id="youtube" value="youtube" autocomplete="off" checked> Youtube
                      </label>
                      <label class="btn btn-info @if($video_platform=='vimeo') active @endif">
                        <input type="radio" wire:model="video_platform" name="video_platform" id="vimeo" value="vimeo" autocomplete="off"> Vimeo
                      </label>
                      <label class="btn btn-info @if($video_platform=='wistia') active @endif">
                        <input type="radio" wire:model="video_platform" name="video_platform" id="youtube" value="wistia" autocomplete="off"> Wistia
                      </label>
                    </div>
                  </div>
                </div>

                {{-- video id --}}
                <div class="col-md-7 col-lg-4">
                  <div class="form-group">
                    <label>{{ trans('adminlte::weevent.platform_video_id', ['platform' => $video_platform]) }}</label>
                    <input wire:model.defer="video_id" type="text" class="form-control" placeholder="{{trans('adminlte::weevent.video_identifier')}} ...">
                    @error('video_id')<small class="form-text text-danger">{{ $message }}</small>@enderror
                  </div>
                </div>

              </div>
            </div>

          </div>

        </div>
        <div class="tab-pane fade @if ($step == 1) active show @endif" id="profile" role="tabpanel" aria-labelledby="cta-tab">
          <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation
            +1
            labore velit, blog sartorial PBR leggings level wes anderson artisan four loko farm-to-table craft
            beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad
            vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar
            helvetica
            VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson
            8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party
            scenester
            stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
        </div>
        <div class="tab-pane fade @if ($step == 2) active show @endif" id="contact" role="tabpanel" aria-labelledby="schedules-tab">
          <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro
            fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone
            skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings
            gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork
            biodiesel
            fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer
            blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
        </div>
      </div>

      <div>

        @if ($step > 0 && $step <= 2) <button type="button" wire:click="decreaseStep" class="btn btn-secondary mr-3">Back</button>
          @endif
          @if ($step <= 2) <button type="button" wire:click="next" class="btn btn-info" @if($steps_active_session['information'] !='' || count($stepsValidated['information']) <> 3) disabled @endif>Next</button>
            @endif
            {{-- @if ($step <= 2) <button type="submit" class="btn btn-info">Next</button>
            @endif --}}

      </div>
    </form>


  </div>
</div>

@push('js')

<script>
  // tabs
  // open card
  $('#cardStep_0_Basic, #cardStep_0_Participants, #cardStep_0_Transmission').on('expanded.lte.cardwidget', function() {
    let sessionName = $(this).data("active-session")
    @this.set('steps_active_session.information', sessionName);
  })

  // close card
  $('#cardStep_0_Basic, #cardStep_0_Participants, #cardStep_0_Transmission').on('collapsed.lte.cardwidget', function() {
    @this.set('steps_active_session.information', '');
  })

  // verifica se algum input está vazio para habilitar o botão confirmar
  function validateInputs() {
    let btnOk = document.querySelector('.bg-success:not([style*="display: none"])');
    if (btnOk) {
      let wrapper = btnOk.parentElement.parentElement.parentElement;
      let inputs = [...wrapper.querySelectorAll('input')];

      function validate() {
        let isIncomplete = inputs.some(input => !input.value);
        btnOk.disabled = isIncomplete;
        btnOk.style.cursor = isIncomplete ? 'not-allowed' : 'pointer';
      }

      wrapper.addEventListener('input', validate);
      validate();
    }
  }

  $(document).ready(function() {
    // input file
    Livewire.on('fileChoosen', id => {
      let inputFile = document.getElementById('input-file-' + id);
      let file = inputFile.files[0];
      // if cancel dialog file
      if (file) {
        let reader = new FileReader();
        reader.onloadend = () => {
          Livewire.emit('fileUpload', id, reader.result);
        }
        reader.readAsDataURL(file);
      }
    });

    Livewire.on('cancelEdit', async () => {
      document.getElementById('frmEvent').reset();

      let btnOk = document.querySelector('.bg-success:not([style*="display: none"])');
      let wrapper = btnOk.parentElement.parentElement.parentElement;
      let inputs = [...wrapper.querySelectorAll('input')];

      inputs.forEach(input => {
        let propName = input.getAttribute('wire:model') ||
          document.getElementById('inputTitle').getAttribute('wire:model.lazy') ||
          document.getElementById('inputTitle').getAttribute('wire:model.defer');
        @this[propName] = input.value;
      });

      await @this.cancelEdit('information', 'basic');
    });
  });

  // console log
  // let logComponentsData = function() {
  function logComponentsData() {
    Livewire.components.components().forEach(component => {
      console.log("%cComponent: " + component.name, "font-weight:bold");
      console.log(component.data);
    });
  };

  document.addEventListener("livewire:load", function(event) {
    logComponentsData();
    validateInputs();

    Livewire.hook('message.processed', (message, component) => {
      logComponentsData();
      validateInputs();
    });

  });

</script>
@endpush
