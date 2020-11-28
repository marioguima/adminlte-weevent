@push('css')
{{-- site de onde peguei o efeito de passar sobre as fotos --}}
{{-- https://codepen.io/dig-lopes/pen/WLVGda?editors=1100 --}}
{{-- css está em 'resources/css/livewire/multiform-event.css' --}}

{{-- ainda não uso alpine --}}
{{-- <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script> --}}
@endpush

<div class="row m-0 p-0">
  <div class="col m-0 p-0 pb-5">

    <ul class="nav nav-pills nav-tabs-custom nav-justified mt-n3" role="tablist">
      <li class="nav-item mr-1 mr-sm-3 d-flex flex-column align-items-center">
        <a class="nav-link @if($step == 0) active show @endif @if($step == 0) disabled @endif" wire:click="jumpToStep(0)" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false"><i class="fal fa-info"></i></a>

        <span class="label-step d-none d-sm-block">{{trans('adminlte::weevent.information')}}</span>
      </li>

      <li class="nav-item mr-1 mr-sm-3 d-flex flex-column align-items-center">
        <a class="nav-link @if($step == 1) active show @endif @if($step == 1 || sizeof($stepsValidated['information']) <> 3 || $active_step_session['information'] != '') disabled @endif" wire:click="jumpToStep(1)" id="live-tab" data-toggle="tab" href="#live" role="tab" aria-controls="live" aria-selected="false"><i class="fal fa-video"></i></a>
        <span class="label-step d-none d-sm-block">{{trans('adminlte::weevent.live')}}</span>
      </li>

      <li class="nav-item d-flex flex-column align-items-center">
        <a class="nav-link @if($step == 2) active show @endif @if($step == 2 || sizeof($stepsValidated['information']) <> 3 || !$stepsValidated['live'] || $active_step_session['information'] != '') disabled  @endif" wire:click="jumpToStep(2)" id="schedules-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="true"><i class="fal fa-calendar-alt"></i></a>
        <span class="label-step d-none d-sm-block">{{trans('adminlte::weevent.schedules')}}</span>
      </li>
    </ul>

    <form wire:submit.prevent="submit" style="margin-top: 7px;" id="frmEvent">

      <div class="tab-content">
        {{-- Information --}}
        <div class="tab-pane fade @if ($step == 0) active show @endif" id="info" role="tabpanel" aria-labelledby="info-tab">

          {{-- Information - Basic --}}
          <div class="card @if ($active_step_session['information'] != 'basic') collapsed-card @endif" data-step-session="information.basic" id="card_Information_Basic">

            <!-- card-header (Basic) -->
            <div class="card-header without-border">
              @if($errors->has('title'))
              <h3 class="card-title text-danger">{{ trans('adminlte::weevent.basic') }} <span class="badge badge-danger">1</span></h3>
              @else
              <h3 class="card-title">{{ trans('adminlte::weevent.basic') }}</h3>
              @endif

              <!-- card-tools -->
              <div class="card-tools">
                @if($active_step_session['information'] != 'basic')
                {{-- verifica se a sessão basic é válida --}}
                @if(in_array('basic',$stepsValidated['information']))
                <span class="text-success">{{ trans('adminlte::weevent.configured') }}</span>
                @else
                <span class="text-danger">{{ trans('adminlte::weevent.no_configured') }}</span>
                @endif
                @endif

                {{-- cancel / confirm --}}
                <button type="button" class="btn btn-default btn-sm mr-2" style="@if($active_step_session['information'] != 'basic') display: none; @endif" wire:click="cancelEditInformationBasic" wire:loading.attr="disabled">Cancelar</button>
                <button type="button" class="btn bg-success btn-sm" style="@if($active_step_session['information'] != 'basic') display: none; @endif" disabled wire:click="validateInformationBasic" wire:loading.attr="disabled">Confirmar</button>
                {{-- edit --}}
                <button type="button" class="btn btn-default btn-sm btn-tools-circle" style="@if($active_step_session['information'] == 'basic') display: none; @endif" data-card-widget="collapse" wire:loading.attr="disabled" @if ($step==0 && $active_step_session['information'] !='' ) disabled @endif><i class="fal fa-pencil"></i></button>
              </div>
            </div>

            <!-- Title -->
            <div class="card-body pt-2 pb-2">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group label-floating is-empty">
                    <label>{{ trans('adminlte::weevent.title_of_event') }}</label>
                    <input wire:model.defer="title" value="{{$title}}" type="text" class="form-control" placeholder="{{trans('adminlte::weevent.title_placeholder')}} ...">
                    @error('title')<small class="form-text text-danger">{{ $message }}</small>@enderror
                  </div>
                </div>
              </div>

            </div>

          </div>

          {{-- Information - Participants and collaborators --}}
          <div class="card @if ($active_step_session['information'] != 'participants') collapsed-card @endif" data-step-session="information.participants" id="card_Information_Participants">

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
                {{-- quantidade de participantes --}}
                @if($active_step_session['information'] != 'participants')
                {{-- verifica se a sessão participants é válida --}}
                @if(in_array('participants',$stepsValidated['information']))
                <span class="text-success">{{ trans_choice('adminlte::weevent.how_many_participants', count($participants)) }}</span>
                @else
                <span class="text-danger">{{ trans_choice('adminlte::weevent.how_many_participants', count($participants)) }}</span>
                @endif
                @endif

                {{-- cancel / confirm --}}
                <button type="button" class="btn btn-default btn-sm mr-2" style="@if($active_step_session['information'] != 'participants') display: none; @endif" wire:click="cancelEditInformationParticipants" wire:loading.attr="disabled">Cancelar</button>
                <button type="button" class="btn bg-success btn-sm" style="@if($active_step_session['information'] != 'participants') display: none; @endif" disabled wire:click="validateInformationParticipants" wire:loading.attr="disabled">Confirmar</button>
                {{-- edit --}}
                <button type="button" class="btn btn-default btn-sm btn-tools-circle" style="@if($active_step_session['information'] == 'participants') display: none; @endif" data-card-widget="collapse" wire:loading.attr="disabled" @if ($step==0 && $active_step_session['information'] !='' ) disabled @endif><i class="fal fa-pencil"></i></button>
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


              <div class="row mb-3" wire:key="post-field-{{ $loop->index }}">

                {{-- full name --}}
                <div class="col-4 d-block d-sm-none align-self-center">
                  <label>{{ trans('adminlte::weevent.full_name') }}:</label>
                </div>
                <div class="col-8 col-sm-3">
                  <div class="form-group">
                    <input type="text" wire:model.defer="participants.{{ $loop->index }}.full_name" value="{{$participants[$loop->index]['full_name']}}" class="form-control-sm w-100" placeholder="{{ @trans('adminlte::weevent.full_name_placeholder') }} ...">
                    @error("participants.{$loop->index}.full_name")<small class="form-text text-danger">{{ $errors->first("participants.{$loop->index}.full_name") }}</small>@enderror
                  </div>
                </div>

                {{-- email --}}
                <div class="col-4 d-block d-sm-none align-self-center">
                  <label>{{ trans('adminlte::weevent.email') }}:</label>
                </div>
                <div class="col-8 col-sm-4">
                  <div class="form-group">
                    <input type="text" wire:model.defer="participants.{{ $loop->index }}.email" value="{{$participants[$loop->index]['email']}}" class="form-control-sm w-100" placeholder="{{ @trans('adminlte::weevent.email_placeholder') }} ...">
                    @error("participants.{$loop->index}.email")<small class="form-text text-danger">{{ $errors->first("participants.{$loop->index}.email") }}</small>@enderror
                  </div>
                </div>

                {{-- role --}}
                <div class="col-8 col-sm-3">
                  <div class="form-row">
                    <div class="custom-control custom-radio mr-2">
                      <input wire:model.defer="participants.{{ $loop->index }}.role" class="custom-control-input" type="radio" id="role_presenter_{{ $loop->index }}" name="role{{ $loop->index }}" value="presenter" checked="">
                      <label for="role_presenter_{{ $loop->index }}" class="custom-control-label">{{ @trans('adminlte::weevent.presenter') }}</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input wire:model.defer="participants.{{ $loop->index }}.role" class="custom-control-input" type="radio" id="role_moderator_{{ $loop->index }}" name="role{{ $loop->index }}" value="moderator" checked="">
                      <label for="role_moderator_{{ $loop->index }}" class="custom-control-label">{{ @trans('adminlte::weevent.moderator') }}</label>
                    </div>
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
                            <input type="file" id="input-file-{{$loop->index}}" value="{{$participants[$loop->index]['photo']}}" data-property-name="participants.{{$loop->index}}.photo" wire:change="$emit('fileChoosen', '{{ $loop->index }}')" accept="image/*">
                          </div>
                        </div>
                      </div>

                    </div>

                    <div class="col-6">
                      <div class="row justify-content-md-center">
                        <div class="col-3">
                          <a href="#" wire:click.prevent="addParticipant()" class="btn p-0 btn-tool @if(!$this->canAddMoreParticipants()) disabled @endif" wire:loading.class="disabled"><i class="fal fa-plus"></i></a>
                        </div>
                        <div class="col-3">
                          @if ($loop->index > 0)
                          <a href="#" wire:click.prevent="removeParticipant({{ $loop->index }})" class="btn p-0 btn-tool" wire:loading.class="disabled"><i class="fal fa-minus"></i></a>
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
          <div class="card @if ($active_step_session['information'] != 'transmission') collapsed-card @endif" data-step-session="information.transmission" id="card_Information_Transmission">

            <!-- card-header (Transmission) -->
            <div class="card-header without-border">
              @if($errors->has('video_id'))
              <h3 class="card-title text-danger">{{ trans('adminlte::weevent.transmission') }} <span class="badge badge-danger">1</span></h3>
              @else
              <h3 class="card-title">{{ trans('adminlte::weevent.transmission') }}</h3>
              @endif

              <!-- card-tools -->
              <div class="card-tools">
                @if($active_step_session['information'] != 'transmission')
                {{-- verifica se a sessão transmission é válida --}}
                @if(in_array('transmission',$stepsValidated['information']))
                <span class="text-success">{{ $video_platform }}</span>
                @else
                <span class="text-danger">{{ trans('adminlte::weevent.no_configured') }}</span>
                @endif
                @endif

                {{-- cancel / confirm --}}
                <button type="button" class="btn btn-default btn-sm mr-2" style="@if($active_step_session['information'] != 'transmission') display: none; @endif" wire:click="cancelEditInformationTransmission" wire:loading.attr="disabled">Cancelar</button>
                <button type="button" class="btn bg-success btn-sm" style="@if($active_step_session['information'] != 'transmission') display: none; @endif" disabled wire:click="validateInformationTransmission" wire:loading.attr="disabled">Confirmar</button>
                {{-- edit --}}
                <button type="button" class="btn btn-default btn-sm btn-tools-circle" style="@if($active_step_session['information'] == 'transmission') display: none; @endif" data-card-widget="collapse" wire:loading.attr="disabled" @if ($step==0 && $active_step_session['information'] !='' ) disabled @endif><i class="fal fa-pencil"></i></button>
              </div>
            </div>

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
                    <input wire:model.defer="video_id" value="{{$video_id}}" type="text" class="form-control" placeholder="{{trans('adminlte::weevent.video_identifier')}} ...">
                    @error('video_id')<small class="form-text text-danger">{{ $message }}</small>@enderror
                  </div>
                </div>

              </div>
            </div>

          </div>

        </div>

        {{-- live --}}
        <div class="tab-pane fade @if ($step == 1) active show @endif" id="live" role="tabpanel" aria-labelledby="live-tab">

          {{-- Live - Offers --}}
          <div class="card @if ($active_step_session['live'] != 'offers') collapsed-card @endif" data-step-session="live.offers" id="card_Live_Offers">

            <!-- card-header (offers) -->
            <div class="card-header without-border">
              @php
              $nOffersWithErrors=0;
              @endphp

              @php
              $nOffersWithErrors =
              count(preg_grep ('/^offers.\w*.name/', array_keys($errors->messages()))) +
              count(preg_grep ('/^offers.\w*.headline/', array_keys($errors->messages()))) +
              count(preg_grep ('/^offers.\w*.text_inside_the_button/', array_keys($errors->messages()))) +
              count(preg_grep ('/^offers.\w*.button_link/', array_keys($errors->messages())));
              @endphp

              @if($nOffersWithErrors)
              <h3 class="card-title text-danger">{{ trans('adminlte::weevent.offers') }} <span class="badge badge-danger">{{$nOffersWithErrors}}</span></h3>
              @else
              <h3 class="card-title">{{ trans('adminlte::weevent.offers') }}</h3>
              @endif

              {{-- card-tools --}}
              <div class="card-tools">
                {{-- quantidade de ofertas --}}
                @if($active_step_session['live'] != 'offers')
                {{-- verifica se a sessão offers é válida --}}
                @if(in_array('offers',$stepsValidated['live']))
                <span class="text-success">{{ trans_choice('adminlte::weevent.how_many_offers', count($offers)) }}</span>
                @else
                <span class="text-danger">{{ trans_choice('adminlte::weevent.how_many_offers', count($offers)) }}</span>
                @endif
                @endif

                {{-- cancel / confirm --}}
                <button type="button" class="btn btn-default btn-sm mr-2" style="@if($active_step_session['live'] != 'offers') display: none; @endif" wire:click="cancelEditOffers" wire:loading.attr="disabled">Cancelar</button>
                <button type="button" class="btn bg-success btn-sm" style="@if($active_step_session['live'] != 'offers') display: none; @endif" disabled wire:click="validateLiveOffers" wire:loading.attr="disabled">Confirmar</button>
                {{-- edit --}}
                <button type="button" class="btn btn-default btn-sm btn-tools-circle" style="@if($active_step_session['live'] == 'offers') display: none; @endif" data-card-widget="collapse" wire:loading.attr="disabled" @if ($active_step_session['live'] !='' ) disabled @endif><i class="fal fa-pencil"></i></button>
              </div>
            </div>

            {{-- List of offers --}}
            <div class="card-body pt-2 pb-2">

              {{-- cabeçalho --}}
              <div class="row mb-1 d-none d-sm-flex">
                <div class="col d-flex">
                  {{-- name --}}
                  <div class="col-sm-2">
                    <label>{{ trans('adminlte::weevent.name_the_offer') }}</label>
                  </div>

                  {{-- cta --}}
                  <div class="col-sm-6 text-center">
                    <label>{{ trans('adminlte::weevent.cta') }}</label>
                  </div>

                  {{-- button_link --}}
                  <div class="col-sm-3">
                    <label>{{ trans('adminlte::weevent.button_link') }}</label>
                  </div>

                  {{-- actions --}}
                  <div class="col-sm-1 order-sm-last" style="width: 50px">
                  </div>
                </div>

              </div>

              {{-- offers --}}
              @foreach ($offers as $offer)

              <div class="row" wire:key="post-field-{{ $loop->index }}">

                {{-- name --}}
                <div class="col-4 d-block d-sm-none align-self-center">
                  <label>{{ trans('adminlte::weevent.name_the_offer') }}:</label>
                </div>
                <div class="col-8 col-sm-2 d-flex align-items-end">
                  <div class="form-group w-100">
                    <input type="text" wire:model.defer="offers.{{ $loop->index }}.name" value="{{$offer['name']}}" class="form-control-sm w-100" placeholder="{{ @trans('adminlte::weevent.name_the_offer_ph') }} ...">
                    @error("offers.{$loop->index}.name")<small class="form-text text-danger">{{ $errors->first("offers.{$loop->index}.name") }}</small>@enderror
                  </div>
                </div>

                {{-- text_inside_the_button --}}
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    @error("offers.{$loop->index}.headline")<small class="form-text text-danger">{{ $errors->first("offers.{$loop->index}.headline") }}</small>@enderror
                    <input type="text" wire:model.defer="offers.{{ $loop->index }}.headline" value="{{$offer['headline']}}" class="form-control-sm w-100 text-center border-right-0 border-left-0" placeholder="{{ @trans('adminlte::weevent.offer_headline_ph') }} ...">
                    <input type="text" wire:model.defer="offers.{{ $loop->index }}.text_inside_the_button" value="{{$offer['text_inside_the_button']}}" class="form-control-lg w-100 btn-orange text-center placeholder-white" placeholder="{{ @trans('adminlte::weevent.example') }}: {{ @trans('adminlte::weevent.text_inside_the_button_ph') }} ...">
                    @error("offers.{$loop->index}.text_inside_the_button")<small class="form-text text-danger">{{ $errors->first("offers.{$loop->index}.text_inside_the_button") }}</small>@enderror
                  </div>
                </div>

                {{-- button_link --}}
                <div class="col-4 d-block d-sm-none align-self-center">
                  <label>{{ trans('adminlte::weevent.button_link') }}:</label>
                </div>
                <div class="col-8 col-sm-3 d-flex align-items-end">
                  <div class="form-group w-100">
                    <input type="text" wire:model.defer="offers.{{ $loop->index }}.button_link" value="{{$offer['button_link']}}" class="form-control-sm w-100" placeholder="{{ @trans('adminlte::weevent.button_link_ph') }} ...">
                    @error("offers.{$loop->index}.button_link")<small class="form-text text-danger">{{ $errors->first("offers.{$loop->index}.button_link") }}</small>@enderror
                  </div>
                </div>

                {{-- actions --}}
                <div class="col-1 col-sm-1 ml-auto">
                  <div class="row justify-content-md-center">
                    <div class="col-3">
                      <a href="#" wire:click.prevent="addOffer()" class="btn p-0 btn-tool" wire:loading.class="disabled"><i class="fal fa-plus"></i></a>
                    </div>
                    <div class="col-3">
                      @if ($loop->index > 0)
                      <a href="#" wire:click.prevent="removeOffer({{ $loop->index }})" class="btn p-0 btn-tool" wire:loading.class="disabled"><i class="fal fa-minus"></i></a>
                      @endif
                    </div>
                  </div>
                </div>

                <hr class="mt-0 mb-3 p-0 col-sm-12 d-sm-none d-block">
              </div>

              @endforeach

            </div>
            <!-- /.card-body -->
          </div>
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

      <div class="row">
        <div class="col">
          @if ($step <= 2) <button type="button" wire:click="next" class="btn btn-info float-right" @if($active_step_session['live'] !='' || count($stepsValidated['live']) <> 1) disabled @endif wire:loading.attr="disabled">Next</button>@endif
            @if ($step > 0 && $step <= 2) <button type="button" wire:click="decreaseStep" class="btn btn-secondary mr-2 float-right" wire:loading.attr="disabled">Back</button>@endif
        </div>
      </div>

    </form>

  </div>
</div>

@push('js')

<script>
  // tabs
  // open card
  $('#card_Information_Basic, #card_Information_Participants, #card_Information_Transmission, #card_Live_Offers').on('expanded.lte.cardwidget', function() {
    let stepName = $(this).data("step-session").split('.')[0];
    let sessionName = $(this).data("step-session").split('.')[1];
    // @this.set('active_step_session.' + stepName, sessionName);
    @this.changeActiveStepSession(stepName, sessionName);
  })

  // close card
  $('#card_Information_Basic, #card_Information_Participants, #card_Information_Transmission, #card_Live_Offers').on('collapsed.lte.cardwidget', function() {
    let stepName = $(this).data("step-session").split('.')[0];
    @this.set('active_step_session.' + stepName, '');
  })

  // verifica se algum input está vazio para habilitar o botão confirmar
  function validateInputs() {
    let btnOk = document.querySelector('.bg-success:not([style*="display: none"])');
    if (btnOk) {
      btnOk.parentElement.parentElement.parentElement.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', validate);
      });

      function validate() {
        let isIncomplete = false;
        let inputs = [];
        let btnOk = document.querySelector('.bg-success:not([style*="display: none"])');
        btnOk.parentElement.parentElement.parentElement.querySelectorAll('input').forEach(input => {
          inputs.push(input);
        });

        function containsBlankField(input, index, arr) {
          if (!input.value && input.type != 'file' && input.type != 'radio' && input.type != 'checkbox') {
            return true
          } else if (input.type == 'file' && !input.defaultValue) {
            return true
          } else if (input.type == 'radio') {
            let nameGroup = input.name;
            return inputs.every(input => input.type == 'radio' && input.name == nameGroup && !input.cheched);
          }
        }
        isIncomplete = inputs.some(containsBlankField);
        btnOk.disabled = isIncomplete;
        btnOk.style.cursor = isIncomplete ? 'not-allowed' : 'pointer';
      }

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
