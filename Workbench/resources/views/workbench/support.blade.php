@extends('layouts.workbench')
@section('title', 'Govt Support & Direction')
@section('view', 'support')

@section('content')
<div class="ph"><div><h2>Govt Support &amp; Direction</h2><p>Where each client sits on the policy-development path, and the publicly-available support at each phase</p></div></div>

<div class="card-p mb-4"><div class="cb" style="font-size:.85rem;color:var(--muted);">
  Mapped to the <strong>Open Policy Making</strong> four-phase model (gov.uk). Each client's AI adoption score places them on the path. Earlier phases need problem-framing and discovery support; later phases need delivery and scaling support.
</div></div>

<div class="row g-3 mb-4" id="phase-cards"></div>

<div class="row g-3">
  <div class="col-lg-6"><div class="card-p"><div class="ch">Portfolio Across Policy Phases</div><div class="cb"><canvas id="chart-phases" height="210"></canvas></div></div></div>
  <div class="col-lg-6"><div class="card-p"><div class="ch">Recommended Direction by Client</div>
    <div class="table-responsive"><table class="table table-hover mb-0">
      <thead><tr><th>Client</th><th>Score</th><th>Phase</th><th>Recommended Support</th></tr></thead>
      <tbody id="support-tbody"></tbody></table></div></div></div>
</div>
<div class="text-muted mt-3" style="font-size:.74rem;">Framework: Open Policy Making Toolkit (gov.uk). Illustrative mappings for demonstration.</div>
@endsection
