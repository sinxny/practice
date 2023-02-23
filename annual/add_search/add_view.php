<div id="app">
    <div class="p-4 mx-5" style="border:dotted; border-radius:50px">
        <div class="form-group form-inline">
            <label for="useDate">연차사용 날짜 : </label>
            <input type="date" class="form-control" id="useDate" name="useDate" required>
            <div class="valid-feedback">Valid.</div>
            <div class="invalid-feedback">Please fill out this field.</div>
        </div>
        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pswd" required>
            <div class="valid-feedback">Valid.</div>
            <div class="invalid-feedback">Please fill out this field.</div>
        </div>
        <div class="form-group form-check">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="remember" required> I agree on blabla.
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Check this checkbox to continue.</div>
            </label>
        </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</div>