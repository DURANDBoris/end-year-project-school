import 'package:front/providers/AccountProvider.dart';

void verifyHardPass(String value, AccountProvider accountProvider) {
  int hard = 0;
  if (value.contains(RegExp(r'[A-Z]'))) {
    hard++;
    accountProvider.isCap = true;
  } else {
    accountProvider.isCap = false;
  }
  if (value.contains(RegExp(r'[a-z]'))) {
    hard++;
    accountProvider.isMin = true;
  } else {
    accountProvider.isMin = false;
  }
  if (value.contains(RegExp(r'[0-9]'))) {
    hard++;
    accountProvider.isNum = true;
  } else {
    accountProvider.isNum = false;
  }
  if (value.contains(RegExp(r"[.!#$%&'*+-/=?^_`{|}~@]"))) {
    hard++;
    accountProvider.isSpecial = true;
  } else {
    accountProvider.isSpecial = false;
  }
  if (value.length >= 8) {
    hard++;
    accountProvider.isLong = true;
  } else {
    accountProvider.isLong = false;
  }
  accountProvider.hard = hard;
}

bool verifyIsEqual(String value1, String value2) {
  if (value1.isEmpty || value1 != value2) {
    return false;
  } else {
    return true;
  }
}
