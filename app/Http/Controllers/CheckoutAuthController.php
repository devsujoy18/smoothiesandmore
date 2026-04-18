<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\SendOtpRequest;
use App\Http\Requests\Checkout\VerifyOtpRequest;
use App\Services\CheckoutAuthService;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;

class CheckoutAuthController extends Controller
{
    public function sendOtp(SendOtpRequest $request, OtpService $otpService): JsonResponse
    {
        $otpService->send(
            email: strtolower($request->string('email')->toString()),
            phone: $request->string('phone')->toString(),
        );

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
        ]);
    }

    public function verifyOtp(
        VerifyOtpRequest $request,
        OtpService $otpService,
        CheckoutAuthService $checkoutAuthService
    ): JsonResponse {
        $email = strtolower($request->string('email')->toString());
        $phone = $request->string('phone')->toString();

        $otpService->verify(
            email: $email,
            phone: $phone,
            otpCode: $request->string('otp')->toString(),
        );

        $checkoutAuthService->loginOrRegister($email, $phone);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.',
            'redirect' => route('checkout.addresses.index'),
        ]);
    }
}
