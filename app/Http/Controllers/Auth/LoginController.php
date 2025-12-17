if ($user->role === 'admin') {
    return redirect()->route('dashboardAdmin');
} elseif ($user->role === 'orangtua') {
    return redirect()->route('dashboardPembayaran');
}
