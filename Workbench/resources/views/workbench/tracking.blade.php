@extends('layouts.workbench')
@section('title', 'Response Tracking')
@section('view', 'tracking')

@section('content')
<div class="ph"><div><h2>Response Tracking</h2><p>The invite funnel: Sent → Opened → Responded and Pending.</p></div></div>

<div class="row g-3 mb-4">
  <div class="col-lg-5"><div class="card-p"><div class="ch">Conversion Funnel</div><div class="cb" id="funnel"></div></div></div>
  <div class="col-lg-7"><div class="card-p"><div class="ch">Responses by Sector</div><div class="cb"><canvas id="chart-resp-sector" height="220"></canvas></div></div></div>
</div>

<div class="card-p"><div class="ch">All Invites</div><div class="table-responsive"><table class="table table-hover mb-0">
  <thead><tr><th>Customer</th><th>Sector</th><th>Sent</th><th>Responded</th><th>Days</th><th>Stage</th><th>Scan Score</th></tr></thead>
  <tbody id="track-tbody"></tbody></table></div></div>
@endsection
