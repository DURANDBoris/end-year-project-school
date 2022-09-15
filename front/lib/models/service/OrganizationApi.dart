import 'dart:convert';
import 'dart:io';
import 'package:dio/dio.dart';
import 'package:front/models/core/Organization.dart';
import 'package:front/models/core/UserOrg.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import '/utils/Constants.dart' as constants;

class OrganizationApi {
  final Dio dio = Dio();
  Future<Organization?> createOrganization(
      {required String name,
      required AuthenticationProvider authentication}) async {
    print("createOrganization");
    print('${constants.URL}/organization');
    Response response;
    var formJson = jsonEncode(
      <String, String>{"name": name},
    );
    try {
      response = await dio.post(
        '${constants.URL}/organization',
        options: Options(
          headers: {'Authorization': "Bearer ${authentication.user.token}"},
          contentType: "application/form-data",
        ),
        data: formJson,
      );
    } catch (e) {
      print("catch createOrganization $e");
      return null;
    }
    print('responseBody createOrganization: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      Map<String, dynamic> json = response.data; //jsonDecode(response.data);
      Organization organization = Organization.fromJson(json);
      return organization;
    }

    return null;
  }

  Future<bool> deleteOrganization(
      {required int idOrganization,
      required AuthenticationProvider authentication}) async {
    print("deleteOrganization");
    print('${constants.URL}/organization/$idOrganization');
    Response response;
    try {
      response = await dio.delete(
        '${constants.URL}/organization/$idOrganization',
        options: Options(
          headers: {'Authorization': "Bearer ${authentication.user.token}"},
          contentType: "application/form-data",
        ),
      );
    } catch (e) {
      print("catch deleteOrganization $e");
      return false;
    }
    print('responseBody deleteOrganization: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      return true;
    }

    return false;
  }

  Future<List<UserOrg>> getOrganizationUsers(
      {required int idOrganization,
      required AuthenticationProvider authentication}) async {
    print("getOrganizationUsers");
    print('${constants.URL}/organization/list/$idOrganization');
    Response response;
    try {
      response = await dio.get(
        '${constants.URL}/organization/list/$idOrganization',
        options: Options(
          headers: {'Authorization': "Bearer ${authentication.user.token}"},
          contentType: "application/form-data",
        ),
      );
    } catch (e) {
      print("catch getOrganizationUsers $e");
      return [];
    }
    print('responseBody getOrganizationUsers: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      Iterable iterable = response.data; //jsonDecode(response.data);
      List<UserOrg> listUser =
          iterable.map((e) => UserOrg.fromJson(e)).toList();
      return listUser;
    }

    return [];
  }

  Future<int> joinOrganization(
      {required int idOrganization,
      required String email,
      required AuthenticationProvider authentication}) async {
    print("joinOrganization");
    print('${constants.URL}/join/$idOrganization');
    var formJson = jsonEncode(
      <String, String>{"email": email},
    );
    Response response;
    try {
      response = await dio.post(
        '${constants.URL}/join/$idOrganization',
        options: Options(
          headers: {'Authorization': "Bearer ${authentication.user.token}"},
          contentType: "application/form-data",
        ),
        data: formJson,
      );
    } catch (e) {
      print("catch joinOrganization $e");
      return HttpStatus.notFound;
    }
    print('responseBody joinOrganization: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      return HttpStatus.ok;
    }

    return HttpStatus.badRequest;
  }

  Future<int> leaveOrganization(
      {required int idOrganization,
      required int idUser,
      required AuthenticationProvider authentication}) async {
    print("leaveOrganization");
    print('${constants.URL}/leave/$idOrganization/$idUser');
    Response response;
    try {
      response = await dio.delete(
        '${constants.URL}/leave/$idOrganization/$idUser',
        options: Options(
          headers: {'Authorization': "Bearer ${authentication.user.token}"},
          contentType: "application/form-data",
        ),
      );
    } catch (e) {
      print("catch leaveOrganization $e");
      return HttpStatus.notFound;
    }
    print('responseBody leaveOrganization: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      return HttpStatus.ok;
    }

    return HttpStatus.badRequest;
  }
}
