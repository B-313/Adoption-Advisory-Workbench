@extends('layouts.workbench')
@section('title', 'Email Management')
@section('view', 'emails')

@section('content')
<div class="ph"><div><h2>Email Management</h2><p>Send AI Readiness Scan invitations to customers and track each one's lifecycle</p></div></div>

<div class="row g-3">
  <div class="col-lg-4"><div class="card-p"><div class="ch">Compose Invite</div><div class="cb">
    <form onsubmit="sendInvite(event)">
      <label class="form-label-sm">Customer</label>
      <select id="em-cust" class="form-select form-select-sm mb-2" required></select>
      <label class="form-label-sm">Service</label>
      <select id="em-svc" class="form-select form-select-sm mb-2"></select>
      <label class="form-label-sm">Subject</label>
      <input id="em-subj" class="form-control form-control-sm mb-2" value="Your complimentary AI Readiness Scan" required>
      <label class="form-label-sm">Message</label>
      <textarea class="form-control form-control-sm mb-2" rows="4">Hi there,

We'd like to offer your team a 10-minute AI Readiness Scan to benchmark you against your sector.</textarea>
      <button class="btn btn-primary btn-sm w-100">Send Invite</button>
    </form>
  </div></div></div>

  <div class="col-lg-8">
    <div class="d-flex gap-2 mb-3 flex-wrap" id="email-pills"></div>
    <div class="card-p"><div class="ch">Sent &amp; Drafts</div><div class="table-responsive"><table class="table table-hover mb-0">
      <thead><tr><th>Customer</th><th>Subject</th><th>Service</th><th>Sent</th><th>Stage</th><th>Advance</th></tr></thead>
      <tbody id="emails-tbody"></tbody></table></div></div>
  </div>
</div>
@endsection
