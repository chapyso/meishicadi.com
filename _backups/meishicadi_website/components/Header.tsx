import React from 'react';

interface HeaderProps {
  userName?: string;
  currentTime?: string;
}

const Header: React.FC<HeaderProps> = ({ 
  userName = "Neha Siddique", 
  currentTime = "Good Afternoon" 
}) => {
  return (
    <div className="bg-dark-900 min-h-screen">
      {/* Main Header Section */}
      <div className="ml-64 p-8">
        <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
          {/* Left: Greeting Section */}
          <div className="flex-1">
            <div className="space-y-4">
              <h1 className="text-greeting text-white">
                {currentTime}, {userName}
              </h1>
              <button className="inline-flex items-center px-6 py-3 bg-primary-500 text-white font-medium rounded-xl hover:bg-primary-600 transition-all duration-200 shadow-card hover:shadow-hover">
                <span className="mr-2">+</span>
                Quick add
              </button>
            </div>
          </div>

          {/* Right: QR Code & Wallet Section */}
          <div className="flex-shrink-0">
            <div className="bg-white rounded-2xl p-6 shadow-card max-w-sm">
              {/* QR Code Section */}
              <div className="text-center mb-6">
                <div className="inline-block p-4 bg-gray-100 rounded-xl mb-4">
                  {/* QR Code Placeholder - Replace with actual QR code */}
                  <div className="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                    <div className="text-center">
                      <div className="w-16 h-16 bg-primary-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
                        <span className="text-white font-bold text-sm">C</span>
                      </div>
                      <div className="text-xs text-gray-600">QR Code</div>
                    </div>
                  </div>
                </div>
                <h3 className="font-semibold text-gray-900 mb-1">Stuart Walker</h3>
                <p className="text-sm text-gray-500">CONSULUM</p>
              </div>

              {/* Action Buttons */}
              <div className="space-y-3">
                <button className="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-200">
                  <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                  Business Link
                </button>
                <button className="w-full px-4 py-2 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-all duration-200">
                  Manage Wallet
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Header;
