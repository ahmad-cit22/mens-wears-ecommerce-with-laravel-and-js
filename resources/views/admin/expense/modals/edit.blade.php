@csrf
<input type="hidden" name="id" value="{{ $expense->id }}">
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Expense Type*</label>
            <select class="form-control @error('expense_id') is-invalid @enderror" name="expense_id" required>
                <option value="">Please select an item</option>
                @foreach ($expense_types as $expense_type)
                    <option value="{{ $expense_type->id }}" {{ $expense_type->id == $expense->expense_id ? 'selected' : '' }}>{{ $expense_type->type }}</option>
                @endforeach
            </select>
            @error('expense_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Bank*</label>
            <select class="form-control @error('bank_id') is-invalid @enderror" name="bank_id" required>
                <option value="">Please select relevant bank</option>
                @foreach ($banks as $bank)
                    <option value="{{ $bank->id }}" {{ $bank->id == $expense->bank_id ? 'selected' : '' }}>{{ $bank->name }}</option>
                @endforeach
            </select>
            @error('bank_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Amount*</label>
            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ $expense->amount }}" required>
            @error('amount')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Date</label>
            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ $expense->date }}">
            @error('date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label>Note</label>
            <input type="text" name="note" class="form-control @error('note') is-invalid @enderror" value="{{ $expense->note }}">
            @error('note')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
