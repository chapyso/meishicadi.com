<?php

namespace App\Http\Controllers;

use App\Models\NFCCard;
use Illuminate\Http\Request;
use Auth;

class NFCCardController extends Controller
{
    public function index()
    {
        $nfcCards = NFCCard::where('user_id', Auth::user()->id)->get();
        return view('nfc.index', compact('nfcCards'));
    }

    public function create()
    {
        return view('nfc.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'card_number' => 'required|unique:nfc_cards,card_number',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $nfcCard = new NFCCard();
        $nfcCard->user_id = Auth::user()->id;
        $nfcCard->card_number = $request->card_number;
        $nfcCard->status = 1;
        $nfcCard->save();

        return redirect()->route('nfc.index')->with('success', __('NFC Card successfully created.'));
    }

    public function edit($id)
    {
        $nfcCard = NFCCard::find($id);
        return view('nfc.edit', compact('nfcCard'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'card_number' => 'required|unique:nfc_cards,card_number,' . $id,
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $nfcCard = NFCCard::find($id);
        $nfcCard->card_number = $request->card_number;
        $nfcCard->save();

        return redirect()->route('nfc.index')->with('success', __('NFC Card successfully updated.'));
    }

    public function destroy($id)
    {
        $nfcCard = NFCCard::find($id);
        $nfcCard->delete();

        return redirect()->route('nfc.index')->with('success', __('NFC Card successfully deleted.'));
    }

    public function changeStatus($id, $status)
    {
        $nfcCard = NFCCard::find($id);
        $nfcCard->status = $status;
        $nfcCard->save();

        return redirect()->route('nfc.index')->with('success', __('NFC Card status successfully updated.'));
    }
}
