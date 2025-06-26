<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VNPay Test Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">VNPay Test Payment</h4>
                        <small class="text-muted">Chỉ dành cho môi trường test</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('vnpay.test.process') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="amount" class="form-label">Số tiền (VND)</label>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       value="10000" min="1000" required>
                                <div class="form-text">Số tiền tối thiểu: 1,000 VND</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="order_info" class="form-label">Nội dung thanh toán</label>
                                <input type="text" class="form-control" id="order_info" name="order_info" 
                                       value="Test payment for Lavish Stay" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Thanh toán qua VNPay
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <h6>Thông tin test VNPay:</h6>
                    <ul class="mb-0">
                        <li>Ngân hàng: NCB</li>
                        <li>Số thẻ: 9704198526191432198</li>
                        <li>Tên chủ thẻ: NGUYEN VAN A</li>
                        <li>Ngày phát hành: 07/15</li>
                        <li>Mật khẩu OTP: 123456</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
