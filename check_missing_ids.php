<?php
$ids = [449, 454, 455, 458];
$businesses = \App\Models\Business::whereIn('id', $ids)->get(['id', 'title', 'created_by']);
dd($businesses->toArray());
