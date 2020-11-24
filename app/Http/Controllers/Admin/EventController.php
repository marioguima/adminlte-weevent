<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(User::find(1)->events);
        $events = Event::all();
        return view('admin.events.listAllEvents', [
            'events' => $events
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.events.newEvent');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = new Event();
        $event->user_id = Auth::id();
        $event->title = $request->title;
        $event->youtube_video_id = $request->youtube_video_id;
        $event->save();

        /**
         * y = Uma representação do ano com dois dígitos / 99 ou 03
         * m = Representação numérica de um mês, com zero à esquerda / 01 a 12
         * d = Dia do mês, 2 dígitos com zero à esquerda / 01 até 31
         * M = Uma representação textual curta de um mês, três letras / Jan a Dec
         * S = Sufixo ordinal inglês para o dia do mês, 2 caracteres / st, nd, rd ou th.
         * H = Formato 24-horas de uma hora com zero à esquerda / 00 até 23
         * i = Minutos com zero à esquerda / 00 até 59
         * s = Segundos, com zero à esquerda / 00 até 59
         * z = O dia do ano (iniciando em 0)
         */
        // $date = Date('ymdMSHisz');
        // $event->public_id = "string".$event->id;
        // $event->save();

        return redirect()->route('event.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return view('admin.events.showEvent', [
            'event' => $event,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        return view('admin.events.editEvent', [
            'event' => $event
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $event->title = $request->title;
        $event->youtube_video_id = $request->youtube_video_id;
        $event->save();

        return redirect()->route('event.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('event.index');
    }
}
