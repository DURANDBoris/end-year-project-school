import 'dart:io';

import 'package:dio/dio.dart';
import 'package:flutter/cupertino.dart';
import 'package:front/models/core/User.dart';
import '/utils/Constants.dart' as constants;

class UploadFileApi {
  final Dio dio = Dio();
  Future<int> uploadFile(
      {required FormData formData,
      required User user,
      required int idFolder}) async {
    Response response;
    try {
      response = await dio.post(
        "${constants.URL}/upload/$idFolder",
        data: formData,
        options: Options(
          headers: {'Authorization': "Bearer ${user.token}"},
          contentType: "application/form-data",
        ),
      );
    } catch (e) {
      print(e);
      return HttpStatus.badRequest;
    }
    print(response.data);

    if (response.statusCode == HttpStatus.ok) return HttpStatus.ok;

    return HttpStatus.notFound;
  }
}
