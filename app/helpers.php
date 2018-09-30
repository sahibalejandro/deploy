<?php

/**
 * Flash an alert message to the session or prints the HTML markup for
 * the previously flashed alert message when no argumets are given.
 */
function alert($level = null, $message = null)
{
    // Render the alert's view when no argument are present.
    if (is_null($level) && is_null($message)) {
        return session()->has('alert')
            ? view('utils.alert')->with(['alert' => session('alert')])->render()
            : null;
    }

    // Use the first argument as message when just one argument is
    // present, then the $level default value will be "success".
    if (is_null($message)) {
        $message = $level;
        $level = 'success';
    }

    session()->flash('alert', compact('level', 'message'));
}
